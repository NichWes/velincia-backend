<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'category',
        'name',
        'brand',
        'variant',
        'unit',
        'price_estimate',
        'is_active',
    ];

    protected $casts = [
        'price_estimate' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    public function projectItems() {
        return $this->hasMany(ProjectItem::class);
    }
}
