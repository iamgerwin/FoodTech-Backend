<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuAddOn extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tenant_id',
        'name',
        'price',
        'is_available',
        'sort_order',
    ];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_add_on_menu_item', 'menu_add_on_id', 'menu_item_id');
    }
}
