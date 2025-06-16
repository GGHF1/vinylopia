<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vinyl;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThirdIterationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sending a message for an order.
     */
    public function test_send_message_for_order(): void
    {
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);
    
        $user1 = User::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);
        $user2 = User::create([
            'fname' => 'Johns',
            'lname' => 'Does',
            'email' => 'john.does@example.com',
            'username' => 'johndoes',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);
    
        $order = Order::create([
            'user_id' => $user1->user_id,
            'seller_id' => $user2->user_id,
            'status' => 'pending',
            'total_amount' => 30.00,
            'shipping_address' => '123 Main Street, Riga, Latvia',
            'payment_method' => 'credit_card',
            'created_at' => now(),
            'updated_at' => now()

        ]);
    
        // Auth as the buyer
        $this->actingAs($user1);
    
        $response = $this->post("/order/{$order->order_id}/message", [
            'message' => 'This is a test message.',
        ]);
    
        $response->assertStatus(302); 
        $this->assertDatabaseHas('order_messages', [
            'order_id' => $order->order_id,
            'message' => 'This is a test message.',
        ]);
    }

    /**
     * Test buyer can pay for order.
     */
    public function test_pay_now_for_order(): void
    {
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);

        $user1 = User::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);
        $user2 = User::create([
            'fname' => 'Johns',
            'lname' => 'Does',
            'email' => 'john.does@example.com',
            'username' => 'johndoes',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);

        $order = Order::create([
            'user_id' => $user1->user_id,
            'seller_id' => $user2->user_id,
            'status' => 'pending',
            'total_amount' => 30.00,
            'shipping_address' => '123 Main Street, Riga, Latvia',
            'payment_method' => 'credit_card',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Auth as the buyer
        $this->actingAs($user1);

        // "Pay Now" button
        $response = $this->get("/order/{$order->order_id}/pay");

        $response->assertStatus(302);
        $response->assertRedirect("/order/{$order->order_id}");
        $response->assertSessionHas('success', 'Payment completed successfully!');

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'status' => 'paid',
        ]);
    }

    /**
     * Test seller can cancel an order.
     */
    public function test_seller_can_cancel_order(): void
    {
       
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);

        $user1 = User::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);
        $user2 = User::create([
            'fname' => 'Johns',
            'lname' => 'Does',
            'email' => 'john.does@example.com',
            'username' => 'johndoes',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);

        $order = Order::create([
            'user_id' => $user1->user_id,
            'seller_id' => $user2->user_id,
            'status' => 'pending',
            'total_amount' => 30.00,
            'shipping_address' => '123 Main Street, Riga, Latvia',
            'payment_method' => 'credit_card',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Auth as the seller
        $this->actingAs($user2);

        // Simulate cancel the order
        $response = $this->post(route('order.cancel', $order->order_id), [
            'reason' => 'The item is out of stock.',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('order.show', $order->order_id));
        $response->assertSessionHas('success', 'Order cancelled successfully');

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'status' => 'cancelled',
        ]);

    }

    

}