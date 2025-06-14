<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Category extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'id',
        'tenant_id',
        'restaurant_id',
        'name',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
