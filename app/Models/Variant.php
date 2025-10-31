<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'values',
    ];
protected $casts = [
    'values' => 'array',
];
  public function products()
    {
        return $this->hasMany(Product::class, 'variant_id');
    }
}
