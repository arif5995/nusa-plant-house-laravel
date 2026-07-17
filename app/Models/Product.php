<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Product extends Model
{

    public static function findBySlug($slug)
    {
        return self::allProducts()->firstWhere('slug', $slug);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
