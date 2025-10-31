<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'title',
    'keyword',
    'price',
    'slug',
    'stock',
    'discount',
    'meta_description',
    'content_description',
    'category_id',
    'variant_id',
    'variant_list',
    'images'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

   protected $casts = [
    'variant_list' => 'array',
    'images' => 'array',
];
}
