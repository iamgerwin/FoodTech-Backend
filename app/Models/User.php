<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Enums\OnboardingState;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
        'user_type',
        'onboarding_state', // Added onboarding state
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the tenant that owns the user.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the customer profile associated with the user.
     */
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the customer addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is approved
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->onboarding_state === \App\Enums\OnboardingState::Approved->value; // Enum-based
    }

    /**
     * Check if user is pending
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->onboarding_state === OnboardingState::Pending->value; // Enum-based
    }

    /**
     * Check if user is declined
     * @return bool
     */
    public function isDeclined(): bool
    {
        return $this->onboarding_state === OnboardingState::Declined->value; // Enum-based
    }

    /**
     * Set onboarding state to approved
     */
    public function setApproved(): void
    {
        $this->onboarding_state = OnboardingState::Approved->value;
        $this->save();
    }

    /**
     * Set onboarding state to pending
     */
    public function setPending(): void
    {
        $this->onboarding_state = OnboardingState::Pending->value;
        $this->save();
    }

    /**
     * Set onboarding state to declined
     */
    public function setDeclined(): void
    {
        $this->onboarding_state = OnboardingState::Declined->value;
        $this->save();
    }
}
