<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Contracts\Tenant as TenantContract;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantContract
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'database',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
