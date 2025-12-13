<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Birthday extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'public-token'
    ];

     protected static function booted()
    {
        static::creating(function ($birthday) {
            $birthday->public_token = Str::random(12); // 🔐
        });
    }
}
