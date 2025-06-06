<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_item_id',
        'variant_id',
        'name',
        'price_modifier',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function variant()
    {
        return $this->belongsTo(MenuItemVariant::class, 'variant_id');
    }
}
