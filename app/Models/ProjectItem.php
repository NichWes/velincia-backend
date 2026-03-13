<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectItem extends Model
{
    protected $fillable = [
        'project_id',
        'material_id',
        'custom_name',
        'qty_needed',
        'qty_purchased',
        'status',
        'notes',
    ];

    protected $casts = [
        'qty_needed' => 'integer',
        'qty_purchased' => 'integer',
    ];

    public const STATUS_NOT_BOUGHT = 'not_bought';
    public const STATUS_PARTIAL    = 'partial';
    public const STATUS_COMPLETE   = 'complete';
    public const STATUS_SUBSTITUTED = 'substituted';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
