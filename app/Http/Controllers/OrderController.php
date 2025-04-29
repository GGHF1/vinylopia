<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\OrderMessage;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    
    public function checkout(Request $request)
    {
        // Validate the minimum required fields
        $request->validate([
            'seller_id' => 'required|exists:users,user_id',
            'shipping_address' => 'required|string|min:10',
            'payment_method' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);
    
        $user = Auth::user();
        $sellerId = $request->seller_id;
        
        // If no shipping address is provided, use the user's stored address
        $shippingAddress = $request->shipping_address ?: $user->address;
        if (empty($shippingAddress)) {
            return redirect()->route('cart')->with('error', 'Please provide a shipping address.');
        }
        
        // Find the cart
        $cart = Cart::where('user_id', $user->user_id)->first();
        
        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        
        // Get items for this specific seller
        $sellerItems = $cart->items()->whereHas('listing', function($query) use ($sellerId) {
            $query->where('user_id', $sellerId);
        })->get();
        
        if ($sellerItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'No items found for this seller.');
        }
        
        // Calculate subtotal (sum of item prices)
        $subtotal = $sellerItems->sum('price');
        
        // Get the shipping cost from the request
        $shipping = floatval($request->shipping_cost);
        
        // Calculate the total (subtotal + shipping)
        $total = $subtotal + $shipping;
        
        // Create the order with the shipping address
        $order = new Order();
        $order->user_id = $user->user_id;
        $order->seller_id = $sellerId;
        $order->total_amount = $total;
        $order->status = 'invoice_sent';
        $order->shipping_address = $shippingAddress;
        $order->payment_method = $request->payment_method;
        $order->save();
        
        // Create order items
        foreach ($sellerItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->order_id;
            $orderItem->listing_id = $cartItem->listing_id;
            $orderItem->price = $cartItem->price;
            $orderItem->save();
            
            $listing = Listing::findOrFail($cartItem->listing_id);
            $listing->status = Listing::STATUS_PLACED;
            $listing->save();

            // Remove from cart
            $cartItem->delete();
        }
        
        $this->addOrderStatus($order, 'pending', $user->user_id);
        $this->addOrderStatus($order, 'invoice_sent', $sellerId);
        
        return redirect()->route('order.show', ['order_id' => $order->order_id])
                        ->with('success', 'Order placed successfully!');
    }

    public function show($orderId)
    {
        $order = Order::with(['items.listing.vinyl', 'user', 'seller', 'statuses.user', 'messages.user'])
                    ->findOrFail($orderId);

        if (Auth::id() != $order->user_id && Auth::id() != $order->seller_id) {
            abort(403, 'Unauthorized');
        }

        $isBuyer = Auth::id() == $order->user_id;
        $isSeller = Auth::id() == $order->seller_id; 
        
        $subtotal = $order->items->sum('price');
        $shipping = $order->total_amount - $subtotal;
        
        return view('order', [
            'order' => $order,
            'isBuyer' => $isBuyer,
            'isSeller' => $isSeller, 
            'subtotal' => $subtotal,
            'shipping' => $shipping,
        ]);
    }

    
    public function sendMessage(Request $request, $orderId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($orderId);

        if (Auth::id() != $order->user_id && Auth::id() != $order->seller_id) {
            abort(403, 'Unauthorized');
        }

        $message = new OrderMessage();
        $message->order_id = $orderId;
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->save();

        return redirect()->route('order.show', ['order_id' => $orderId])
                        ->with('success', 'Message sent');
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|string|in:invoice_sent,paid,in_progress,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($orderId);

        $isBuyer = Auth::id() == $order->user_id;
        $isSeller = Auth::id() == $order->seller_id;

        if (!$isBuyer && !$isSeller) {
            abort(403, 'Unauthorized');
        }

        $newStatus = $request->status;
        
        if ($newStatus == 'paid' && !$isBuyer) {
            abort(403, 'Only buyers can mark orders as paid');
        }
        
        if (($newStatus == 'in_progress' || $newStatus == 'shipped') && !$isSeller) {
            abort(403, 'Only sellers can update shipping status');
        }
        
        $order->status = $newStatus;
        $order->save();

        $this->addOrderStatus($order, $newStatus, Auth::id());

        $statusMessages = [
            'invoice_sent' => 'Invoice sent to buyer',
            'paid' => 'Payment received',
            'in_progress' => 'Order is being processed',
            'shipped' => 'Order has been shipped',
            'delivered' => 'Order has been delivered',
            'cancelled' => 'Order has been cancelled',
        ];

        return redirect()->route('order.show', ['order_id' => $orderId])
                        ->with('success', $statusMessages[$newStatus]);
    }
    
    
    public function payNow($orderId)
    {
        $order = Order::with('items.listing')->findOrFail($orderId);
        
        // Security check - only buyer can pay
        if (Auth::id() != $order->user_id) {
            abort(403, 'Unauthorized');
        }
        
        // Update order status
        $order->status = 'paid';
        $order->save();

        foreach ($order->items as $item) {
            $listing = $item->listing;
            $listing->status = Listing::STATUS_SOLD;
            $listing->save();
        }
        
        // Add status to timeline
        $this->addOrderStatus($order, 'paid', Auth::id());
        
        return redirect()->route('order.show', ['order_id' => $orderId])
                        ->with('success', 'Payment completed successfully!');
    }

    // List all orders for the user
    public function orders()
    {
        $user = Auth::user();
        
        $buyerOrders = Order::where('user_id', $user->user_id)
                           ->with(['seller', 'items.listing.vinyl'])
                           ->latest()
                           ->get();
                           
        $sellerOrders = Order::where('seller_id', $user->user_id)
                            ->with(['user', 'items.listing.vinyl'])
                            ->latest()
                            ->get();
        
        return view('orders', [
            'buyerOrders' => $buyerOrders,
            'sellerOrders' => $sellerOrders,
        ]);
    }
    
    // Add order status to the timeline
    private function addOrderStatus($order, $status, $userId)
    {
        $orderStatus = new OrderStatus();
        $orderStatus->order_id = $order->order_id;
        $orderStatus->user_id = $userId;
        $orderStatus->status = $status;
        $orderStatus->save();
        
        return $orderStatus;
    }

    
    public function cancel(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
    
        $order = Order::with('items.listing')->findOrFail($orderId);
        
        // Security check - only seller can cancel
        if (Auth::id() != $order->seller_id) {
            abort(403, 'Only sellers can cancel orders');
        }
        
        // Add cancellation message
        $message = new OrderMessage();
        $message->order_id = $orderId;
        $message->user_id = Auth::id();
        $message->message = "Order cancelled: " . $request->reason;
        $message->save();
        
        // Update order status
        $order->status = 'cancelled';
        $order->save();
        
        // Relist the items (update listing status back to listed)
        foreach ($order->items as $item) {
            $listing = $item->listing;
            $listing->status = Listing::STATUS_LISTED;
            $listing->save();
        }
        
        // Add status to timeline
        $this->addOrderStatus($order, 'cancelled', Auth::id());
        
        return redirect()->route('order.show', ['order_id' => $orderId])
                        ->with('success', 'Order cancelled successfully');
    }

}