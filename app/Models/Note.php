<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'created_by'];

    public function notable()
    {
        return $this->morphTo();
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
