<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'menu_item_id',
        'name',
        'type',
        'price_modifier',
        'is_required',
        'is_available',
        'sort_order',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
