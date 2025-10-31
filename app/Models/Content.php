<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use SoftDeletes;

    protected $table = 'content'; // table name migration ke mutabiq

    protected $fillable = [
        'page_id',
        'h1',
        'h2',
        'h3',
        'p1',
        'p2',
        'title',
        'image',
        'design',
        'keyword',
        'content'
    ];

    protected $dates = ['deleted_at']; 

    // Page relation
    public function page()
    {
           return $this->belongsTo(Page::class, 'page_id', 'id');
    }


}
