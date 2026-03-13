<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = [
        'order_id',
        'project_item_id',
        'material_id',
        'name_snapshot',
        'qty',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function projectItem()
    {
        return $this->belongsTo(ProjectItem::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}