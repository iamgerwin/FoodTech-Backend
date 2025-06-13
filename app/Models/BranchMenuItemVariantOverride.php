<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchMenuItemVariantOverride extends Model
{
    public function getEffectiveNameAttribute() {
        return $this->custom_name ?? optional($this->menuItemVariant)->name;
    }
    public function getEffectivePriceModifierAttribute() {
        return $this->custom_price_modifier ?? optional($this->menuItemVariant)->price_modifier;
    }
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'branch_id',
        'menu_item_variant_id',
        'custom_name',
        'custom_price_modifier',
    ];
    public function branch() { return $this->belongsTo(RestaurantBranch::class, 'branch_id'); }
    public function menuItemVariant() { return $this->belongsTo(MenuItemVariant::class, 'menu_item_variant_id'); }
}
