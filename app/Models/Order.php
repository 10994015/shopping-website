<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'total_price',
        'created_by',
        'updated_by'
    ];

    public function isPaid(){
        return $this->status === 'paid';
    }
    public function orderDate(){
        return $this->created_at->format('Y/m/d');
    }
    public function payment():HasOne
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }
    public function items():HasMany{
        return $this->hasMany(OrderItem::class);
    }
}
