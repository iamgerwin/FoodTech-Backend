<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

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

    public function menuCategories()
    {
        return $this->hasMany(\App\Models\MenuCategory::class, 'restaurant_id');
    }
}
