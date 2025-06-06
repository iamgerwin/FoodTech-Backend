<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_number',
        'customer_id',
        'restaurant_id',
        'branch_id',
        'delivery_address_id',
        'status',
        'order_type',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'delivery_fee',
        'service_charge',
        'discount_amount',
        'total_amount',
        'estimated_prep_time',
        'estimated_delivery_time',
        'placed_at',
        'confirmed_at',
        'ready_at',
        'dispatched_at',
        'delivered_at',
        'special_instructions',
        'cancellation_reason',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function branch()
    {
        return $this->belongsTo(RestaurantBranch::class, 'branch_id');
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
