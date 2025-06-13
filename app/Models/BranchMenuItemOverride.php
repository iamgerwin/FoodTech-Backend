<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchMenuItemOverride extends Model
{
    public function getEffectiveNameAttribute() {
        return $this->custom_name ?? optional($this->menuItem)->name;
    }
    public function getEffectivePriceAttribute() {
        return $this->custom_price ?? optional($this->menuItem)->price;
    }
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'branch_id',
        'menu_item_id',
        'custom_name',
        'custom_price',
        'custom_description',
    ];
    public function branch() { return $this->belongsTo(RestaurantBranch::class, 'branch_id'); }
    public function menuItem() { return $this->belongsTo(MenuItem::class, 'menu_item_id'); }
}
