<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;
use App\Models\Sale;
use App\Models\Product;

class NoteTableSeeder extends Seeder
{
    public function run()
    {
        $sales = Sale::all();
        $products = Product::all();

        foreach ($sales as $sale) {
            Note::factory(1)->create([
                'notable_type' => Sale::class,
                'notable_id' => $sale->id,
            ]);
        }

        foreach ($products as $product) {
            Note::factory(1)->create([
                'notable_type' => Product::class,
                'notable_id' => $product->id,
            ]);
        }
    }
}
