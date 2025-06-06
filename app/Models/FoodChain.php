<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodChain extends Model
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
        'owner_id',
        'name',
        'description',
        'logo',
        'contact_email',
        'contact_phone',
        'business_license',
        'tax_id',
        'is_active',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
