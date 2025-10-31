<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 SoftDeletes import

class Page extends Model
{
    use HasFactory, SoftDeletes; // 👈 trait add کیا گیا

    protected $fillable = [
        'p_type_name',
        'p_name',
        'p_slug',
    
    ];
}
