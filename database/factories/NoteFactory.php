<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition()
    {
        // Randomly select notable: Sale or Product
        $types = [Sale::class, Product::class];
        $notableType = $this->faker->randomElement($types);
        $notableId = $notableType::inRandomOrder()->first()->id ?? null;

        return [
            'notable_type' => $notableType,
            'notable_id' => $notableId,
            'body' => $this->faker->sentence,
        ];
    }
}
