<!-- Product Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-10 overflow-hidden">
    @foreach ($products as $product)
        <x-product-card :id="$product['id']" :image="$product['image']" :title="$product['title']" :price="$product['price']" :discountPrice="$product['discountPrice']"
            :badge="$product['badge']" :stock="$product['stock']" :categories="$product['categories']" />
    @endforeach
</div>
