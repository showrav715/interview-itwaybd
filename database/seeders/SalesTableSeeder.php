<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Product;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        // 500 sales
        Sale::factory(500)->create()->each(function($sale){
            // 1-5 items per sale
            $itemsCount = rand(1,5);
            $products = Product::inRandomOrder()->take($itemsCount)->get();

            $total = 0;
            foreach($products as $product){
                $quantity = rand(1,10);
                $price = $product->price;
                $discount = rand(0, round($price*$quantity*0.2));
                $lineTotal = round(($quantity*$price)-$discount,2);
                $total += $lineTotal;

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'total' => $lineTotal,
                ]);
            }

            // Update sale total
            $sale->total = round($total,2);
            $sale->save();
        });
    }
}
