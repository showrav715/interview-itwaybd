<?php

use Illuminate\Support\Arr;

if (! function_exists('calculateSaleTotal')) {
    function calculateSaleTotal(array $items): float
    {
        $total = 0.0;
        foreach ($items as $it) {
            $qty = (float) Arr::get($it, 'quantity', 0);
            $price = (float) Arr::get($it, 'price', 0);
            $discount = (float) Arr::get($it, 'discount', 0);
            $line = ($qty * $price) - $discount;
            $total += $line;
        }
        return round($total, 2);
    }


    if (! function_exists('formatCurrency')) {
        function formatCurrency(float $amount): string
        {
            return number_format($amount, 2) . ' ' . env('CURRENCY', 'BDT');
        }
    }
}
