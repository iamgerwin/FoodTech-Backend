<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'reviewer_id',
        'reviewee_type',
        'reviewee_id',
        'rating',
        'comment',
        'is_anonymous',
        'is_approved',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // reviewee: morphTo for restaurant or driver
    public function reviewee()
    {
        return $this->morphTo(null, 'reviewee_type', 'reviewee_id');
    }
}
