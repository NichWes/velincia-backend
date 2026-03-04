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
    public function projectItems() {
        return $this->hasMany(ProjectItem::class);
    }
}
