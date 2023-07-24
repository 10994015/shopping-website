<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeUsages extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'discount_id',
        'order_id',
        'discount_amount',
        'used_at'
    ];
}
