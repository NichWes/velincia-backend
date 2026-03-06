<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = [
        'project_id',
        'user_id',
        'order_code',
        'order_type',
        'status',
        'delivery_method',
        'delivery_address',
        'subtotal',
        'shipping_fee',
        'total_amount',
        'is_applied_to_project',
        'applied_to_project_at',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_WAITING_ADMIN = 'waiting_admin';
    public const STATUS_WAITING_PAYMENT = 'waiting_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_READY_PICKUP = 'ready_pickup';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}