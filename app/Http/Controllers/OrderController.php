<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    //

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $orderNumber = $this->orderService->generateOrderNumber();
        $total = $this->orderService->calculateTotal($validated['items']);

        $order = Order::create([
            'order_number' => $orderNumber,
            'total' => $total,
            'status' => $total > 1000 ? 'pending' : 'approved'
        ]);

        foreach ($validated['items'] as $item) {
            $order->items()->create($item);
        }

        $order->history()->create(['status' => $order->status]);

        return response()->json($order, 201);
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order already processed'], 400);
        }

        $order->update(['status' => 'approved']);
        $order->history()->create(['status' => 'approved']);

        return response()->json(['message' => 'Order approved']);
    }

    public function history($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order->history);
    }


}
