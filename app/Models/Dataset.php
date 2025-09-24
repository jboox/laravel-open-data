<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'api_url',
        'category_id',
        'created_by',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime', // ⏰ otomatis jadi Carbon
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function values()
    {
        return $this->hasMany(DatasetValue::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
