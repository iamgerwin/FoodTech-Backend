<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Contracts\Tenant as TenantContract;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use App\Models\Restaurant;
use App\Models\User;

class Tenant extends BaseTenant implements TenantContract
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

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'tenant_id');
    }
}
