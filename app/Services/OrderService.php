<?php
namespace App\Services;

use App\Models\Order;



class OrderService
{
    public function generateOrderNumber()
    {
        $lastOrder = Order::latest()->first();
        $nextNumber = $lastOrder ? (int) substr($lastOrder->order_number, -5) + 1 : 1;
        return 'ORD' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function calculateTotal($items)
    {
        return collect($items)->sum(fn($item) => $item['quantity'] * $item['price']);
    }
}