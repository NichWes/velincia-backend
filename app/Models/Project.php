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

    public function user() {
        return $this -> belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(ProjectItem::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
