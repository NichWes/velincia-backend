<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id', 
        'title',
        'project_type',
        'status',
        'budget_target',
        'notes'
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';  
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(ProjectItem::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
