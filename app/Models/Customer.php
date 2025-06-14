<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'latitude',
        'longitude',
    ];
    public function user() { return $this->belongsTo(User::class); }
}
