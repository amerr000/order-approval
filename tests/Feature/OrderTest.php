<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation()
    {
        $response = $this->postJson('/api/orders', [
            'items' => [
                ['item_name' => 'Item 1', 'quantity' => 2, 'price' => 500]
            ]
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['order_number', 'total', 'status']);
    }

    public function test_order_requires_approval()
    {
        $response = $this->postJson('/api/orders', [
            'items' => [
                ['item_name' => 'Expensive Item', 'quantity' => 1, 'price' => 1200]
            ]
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['status' => 'pending']);
    }

    public function test_order_can_be_approved()
    {
        $order = Order::create([
            'order_number' => 'ORD00002',
            'total' => 1200,
            'status' => 'pending'
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/approve");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Order approved']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'approved']);
    }

    public function test_order_history_returns_correct_status_changes()
    {
        $order = Order::create([
            'order_number' => 'ORD00002',
            'total' => 1200,
            'status' => 'pending'
        ]);

        $order->update(['status' => 'approved']);

        $response = $this->getJson("/api/orders/{$order->id}/history");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'order_id', 'status', 'created_at', 'updated_at']
                 ]);
    }
}
