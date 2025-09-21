<?php

namespace Database\Factories;

use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition()
    {
        $product = Product::inRandomOrder()->first();
        $quantity = $this->faker->numberBetween(1,10);
        $price = $product->price;
        $discount = $this->faker->numberBetween(0, round($price*$quantity*0.2));

        return [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $discount,
            'total' => round(($quantity*$price)-$discount,2),
        ];
    }
}
