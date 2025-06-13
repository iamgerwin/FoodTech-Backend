<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantBranch extends Model
{
    public function branchMenuItemOverrides() {
        return $this->hasMany(BranchMenuItemOverride::class, 'branch_id');
    }
    public function branchMenuItemVariantOverrides() {
        return $this->hasMany(BranchMenuItemVariantOverride::class, 'branch_id');
    }
    use HasFactory;

    /**
     * The primary key type is string (UUID).
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key is non-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    use HasFactory;

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
        'restaurant_id',
        'manager_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'is_active',
        'accepts_orders',
        'delivery_radius_km',
        'opens_at',
        'closes_at',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
