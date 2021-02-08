<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_title',
    ];

    public function news()
    {
        return $this->belongsToMany('App\Models\News');
    }

}
