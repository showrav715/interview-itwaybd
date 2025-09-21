<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sale extends Model
{

    use SoftDeletes, HasFactory;
    protected $fillable = ['user_id', 'sale_date', 'total'];
    protected $casts = ['sale_date' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
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

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 2) . ' ' . env('CURRENCY', 'BDT');
    }
    public function setNotesAttribute($value)
    {
        $this->attributes['notes'] = $value ? Str::title($value) : null;
    }
}
