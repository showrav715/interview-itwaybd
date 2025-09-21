<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use function calculateSaleTotal;

class SaleService
{

    public static function createSale(array $saleData, array $itemsData): Sale
    {
        return DB::transaction(function () use ($saleData, $itemsData) {
            $total = calculateSaleTotal($itemsData);
            $saleData['total'] = $total;

            $sale = Sale::create($saleData);

            foreach ($itemsData as $it) {
                $lineTotal = (($it['quantity'] ?? 0) * ($it['price'] ?? 0)) - ($it['discount'] ?? 0);
                $sale->items()->create([
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                    'discount' => $it['discount'] ?? 0,
                    'total' => round($lineTotal, 2)
                ]);
            }

            return $sale;
        });
    }

    public static function updateSale(Sale $sale, array $saleData, array $itemsData): Sale
    {
        return DB::transaction(function () use ($sale, $saleData, $itemsData) {

            $sale->update($saleData);

            $existingIds = $sale->items()->pluck('id')->toArray();
            $newItemIds = [];

            foreach ($itemsData as $item) {
                $saleItem = $sale->items()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                        'discount'   => $item['discount'] ?? 0,
                    ]
                );
                $newItemIds[] = $saleItem->id;
            }

            $toDelete = array_diff($existingIds, $newItemIds);
            if (count($toDelete)) {
                $sale->items()->whereIn('id', $toDelete)->delete();
            }
            $sale->total = calculateSaleTotal($sale->items);
            $sale->save();

            return $sale;
        });
    }
}
