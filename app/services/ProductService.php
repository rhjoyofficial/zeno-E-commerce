<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Exception;

class ProductService
{
    /**
     * Create a new product.
     */
    public function createProduct(array $data, ?UploadedFile $primaryImage, ?array $additionalImages): Product
    {
        DB::beginTransaction();

        try {
            $product = Product::create([
                'title' => $data['title'],
                'short_description' => $data['short_description'],
                'price' => $data['price'],
                'stock_quantity' => $data['stock_quantity'] ?? 0,
                'stock_alert' => $data['stock_alert'] ?? null,
                'status' => $data['status'],
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'slug' => Str::slug($data['title']) . '-' . uniqid(),
                'sku' => $data['sku'] ?? $this->generateSku('PROD', $data['title']),
                'has_variants' => $data['has_variants'] ?? false,
            ]);

            if (!empty($data['tags'])) {
                $product->tags()->sync($data['tags']);
            }

            if ($primaryImage) {
                $this->storeProductImage($product, $primaryImage, true);
            }

            if ($additionalImages) {
                foreach ($additionalImages as $image) {
                    $this->storeProductImage($product, $image, false);
                }
            }

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data, ?UploadedFile $primaryImage, ?array $additionalImages, array $existingImageIds = []): Product
    {
        DB::beginTransaction();

        try {
            $product->update([
                'title' => $data['title'],
                'short_description' => $data['short_description'],
                'price' => $data['price'],
                'stock_quantity' => $data['stock_quantity'] ?? 0,
                'stock_alert' => $data['stock_alert'] ?? null,
                'status' => $data['status'],
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'has_variants' => $data['has_variants'] ?? false,
            ]);

            $product->tags()->sync($data['tags'] ?? []);

            if ($primaryImage) {
                $oldPrimary = $product->images()->where('is_primary', true)->first();
                if ($oldPrimary) {
                    Storage::disk('public')->delete($oldPrimary->image_path);
                    $oldPrimary->delete();
                }
                $this->storeProductImage($product, $primaryImage, true);
            }

            // Remove unselected images
            $imagesToDelete = $product->images()
                ->where('is_primary', false)
                ->whereNotIn('id', $existingImageIds)
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            if ($additionalImages) {
                $currentAdditionalCount = $product->images()->where('is_primary', false)->count();
                $remainingSlots = 5 - $currentAdditionalCount;

                if ($remainingSlots > 0) {
                    $imagesToProcess = array_slice($additionalImages, 0, $remainingSlots);
                    foreach ($imagesToProcess as $image) {
                        $this->storeProductImage($product, $image, false);
                    }
                }
            }

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Delete a product completely.
     */
    public function deleteProduct(Product $product): void
    {
        DB::beginTransaction();

        try {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $product->tags()->detach();
            $product->variants()->delete();
            $product->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Store product image internally.
     */
    protected function storeProductImage(Product $product, UploadedFile $file, bool $isPrimary): void
    {
        $path = $file->store('products/images', 'public');
        $product->images()->create([
            'image_path' => $path,
            'is_primary' => $isPrimary
        ]);
    }

    /**
     * Generate SKU.
     */
    protected function generateSku(string $prefix, string $title): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', $title), 0, 3));
        $random = mt_rand(10000, 99999);
        return $prefix . '-' . $random;
    }
}
