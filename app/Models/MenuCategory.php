<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
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
            // Set tenant_id from parent restaurant if not set
            if (empty($model->tenant_id) && $model->restaurant_id) {
                $model->tenant_id = Restaurant::find($model->restaurant_id)?->tenant_id;
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'restaurant_id',
        'name',
        'description',
        'image',
        'sort_order',
        'is_active',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
}
