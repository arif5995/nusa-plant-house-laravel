<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'price',
        'weight',
    ];
    public static function findBySlug($slug)
    {
        return self::allProducts()->firstWhere('slug', $slug);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
