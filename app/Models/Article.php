<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'author_id',
        'published_at',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime', // ⏰ bisa format('d M Y')
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
