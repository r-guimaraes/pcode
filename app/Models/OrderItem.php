<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'orders_items';
    protected $fillable = [
        'quantity',
    ];

    public function item(): HasOne
    {
        return $this->hasOne(Item::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
