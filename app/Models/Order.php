<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasFoodOrderStateMachine;

class Order extends Model
{
    use HasFactory, HasFoodOrderStateMachine;
    use HasFactory;

    // Order states and transitions are now managed in App\Domain\Order\OrderState

    /**
     * The primary key type is string (UUID).
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key is non-incrementing.
     * @var bool
     */
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }


    protected $fillable = [
        // Add 'status' to fillable if not already present
        'status',
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
