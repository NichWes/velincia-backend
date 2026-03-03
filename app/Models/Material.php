<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public function projectItems() {
        return $this->hasMany(ProjectItem::class);
    }
}
