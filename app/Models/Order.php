<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_number', 'total', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class);
    }
}
