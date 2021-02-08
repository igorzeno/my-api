<?php

namespace App\Models;

use App\Models\User;
use App\Models\Newtag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'details',
        'created_by',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tag()
    {
        return $this->hasManyThrough(
            Tag::class,
            Newtag::class,
            'news_id',
            'id',
            'id',
            'tag_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
