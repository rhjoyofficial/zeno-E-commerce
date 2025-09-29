<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public int $id;
    public string $image;
    public string $title;
    public float $price;
    public ?float $discountPrice;
    public ?string $badge;
    public bool $stock;
    public array $categories;

    /**
     * Create a new component instance.
     */
    public function __construct(
        int $id,
        string $image,
        string $title,
        float $price,
        ?float $discountPrice = null,
        ?string $badge = null,
        bool $stock = true,
        array $categories = []
    ) {
        $this->id = $id;
        $this->image = $image;
        $this->title = $title;
        $this->price = $price;
        $this->discountPrice = $discountPrice;
        $this->badge = $badge;
        $this->stock = $stock;
        $this->categories = $categories;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-card');
    }
}
