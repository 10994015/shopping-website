<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'usage_count',
        'usage_limit',
        'minimum_spend',
        'percentage',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];
}
