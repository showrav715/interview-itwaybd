<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'price', 'discount', 'total'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function product()
    {
         return $this->belongsTo(Product::class);
    }
}
