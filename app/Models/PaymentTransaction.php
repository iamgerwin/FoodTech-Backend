<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

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
        'order_id',
        'transaction_id',
        'payment_type',
        'payment_subtype',
        'payment_details',
        'payment_provider',
        'provider_transaction_id',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'processed_at',
    ];

    protected $appends = ['payment_type_label', 'payment_subtype_label'];

    protected $casts = [
        'gateway_response' => 'array',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected function paymentTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => PaymentTypeEnum::getTypes()[$this->payment_type] ?? $this->payment_type
        );
    }

    protected function paymentSubtypeLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->payment_subtype)) {
                    return null;
                }
                return PaymentTypeEnum::getSubTypeLabels()[$this->payment_subtype] ?? $this->payment_subtype;
            }
        );
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
