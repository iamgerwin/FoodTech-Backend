<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'food_chain_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner_image',
        'cuisine_type',
        'average_prep_time',
        'minimum_order_amount',
        'delivery_fee',
        'service_charge_percentage',
        'is_active',
        'opens_at',
        'closes_at',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function foodChain()
    {
        return $this->belongsTo(FoodChain::class);
    }

    public function branches()
    {
        return $this->hasMany(RestaurantBranch::class);
    }
}
