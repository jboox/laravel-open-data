<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatasetValue extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'dataset_id',
        'region_id',
        'date',
        'value',
        'meta'
    ];

    protected $casts = [
        'date' => 'datetime',   // ⏰ supaya bisa format('Y-m-d') / format('Y')
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
