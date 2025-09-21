<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'sku', 'price', 'stock', 'description'];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function latestNote()
    {
        return $this->morphOne(Note::class, 'notable')->latestOfMany();
    }
}
