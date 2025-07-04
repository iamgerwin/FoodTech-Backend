<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
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
        });
    }

    protected $fillable = [
        'tenant_id',
        'restaurant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'discounted_price',
        'preparation_time',
        'calories',
        'ingredients',
        'allergens',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'is_spicy',
        'spice_level',
        'is_available',
        'is_featured',
        'sort_order',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(MenuItemVariant::class);
    }

    /**
     * The menu add-ons that belong to the menu item.
     */
    public function menuAddOns()
    {
        return $this->belongsToMany(
            MenuAddOn::class,
            'menu_add_on_menu_item', // pivot table name
            'menu_item_id',          // foreign key on pivot table for this model
            'menu_add_on_id'         // foreign key on pivot table for related model
        );
    }
}
