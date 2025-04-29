@extends('layouts.app')

@section('title', 'Order Details')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/orderstyle.css') }}">
@endsection

@section('content')
<div class="order-container">
    <div class="order-header">
        <div class="order-title">
            <h1>Order {{ $order->getOrderNumber() }}</h1>
            <div class="order-date">
                Created: {{ $order->created_at->format('M d, Y H:i A') }}
                @if($order->created_at->diffInDays() < 30)
                    <span class="date-ago">({{ $order->created_at->diffForHumans() }})</span>
                @endif
            </div>
        </div>
        <div class="order-status {{ $order->getStatusClass() }}">
            {{ $order->getStatusLabel() }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="order-grid">
        <div class="order-details-column">
            <div class="order-panel">
                <h2>Order Details</h2>
                <div class="info-group">
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value status-badge {{ $order->getStatusClass() }}">{{ $order->getStatusLabel() }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ $isBuyer ? 'Seller' : 'Buyer' }}:</span>
                        <span class="info-value">{{ $isBuyer ? $order->seller->username : $order->user->username }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Shipping Address:</span>
                        <div class="info-value address">
                            {{ nl2br(e($order->shipping_address)) }}
                        </div>
                    </div>
                </div>

                @if($order->status == 'invoice_sent' && $isBuyer)
                    <div class="action-buttons">
                        <a href="{{ route('order.pay', $order->order_id) }}" class="btn btn-primary">
                            <i class="fas fa-lock"></i> Pay Now
                        </a>
                    </div>
                @endif

                <!-- Only show cancel section to seller and if order is not already cancelled or delivered -->
                @if($isSeller && $order->status != 'cancelled' && $order->status != 'delivered')
                    <div class="cancel-section">
                        <button class="btn-link" id="toggle-cancel-form">Cancel this order</button>
                        <div class="cancel-form" id="cancel-form" style="display:none;">
                            <form action="{{ route('order.cancel', $order->order_id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="reason">Cancellation reason:</label>
                                    <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                            </form>
                        </div>
                    </div>
                @endif
                
                <!-- Add this to display abandonment warning for invoice_sent orders -->
                @if($order->status == 'invoice_sent')
                    <div class="abandonment-warning">
                        <p>Note: Orders with unpaid invoices will be automatically cancelled after 4 days.</p>
                        <p>Order created: {{ $order->created_at->format('M d, Y H:i') }}</p>
                        <p>Cancellation deadline: {{ $order->created_at->addDays(4)->format('M d, Y H:i') }}</p>
                    </div>
                @endif
            </div>

            <div class="order-panel">
                <h2>Items</h2>
                <div class="order-items">
                    @foreach($order->items as $item)
                        <div class="order-item">
                            <div class="item-image">
                                <img src="{{ $item->listing->vinyl->cover }}" alt="{{ $item->listing->vinyl->title }}">
                            </div>
                            <div class="item-details">
                                <h3>{{ $item->listing->vinyl->artist }} - {{ $item->listing->vinyl->title }}</h3>
                                <div class="item-meta">
                                    <span class="condition">Media: {{ $item->listing->vinyl_condition }}</span>
                                    <span class="condition">Sleeve: {{ $item->listing->cover_condition }}</span>
                                </div>
                                @if($item->listing->comments)
                                    <div class="item-comments">
                                        <small>{{ \Illuminate\Support\Str::limit($item->listing->comments, 100) }}</small>
                                    </div>
                                @endif
                            </div>
                            <div class="item-price">
                                €{{ number_format($item->price, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>€{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>€{{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>€{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($isSeller && $order->status == 'paid')
                <div class="order-panel seller-actions">
                    <h2>Seller Actions</h2>
                    <form action="{{ route('order.update-status', $order->order_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn btn-primary">Mark as In Progress</button>
                    </form>
                </div>
            @endif

            @if($isSeller && $order->status == 'in_progress')
                <div class="order-panel seller-actions">
                    <h2>Seller Actions</h2>
                    <form action="{{ route('order.update-status', $order->order_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="shipped">
                        <button type="submit" class="btn btn-primary">Mark as Shipped</button>
                    </form>
                </div>
            @endif

            @if($isBuyer && $order->status == 'shipped')
                <div class="order-panel buyer-actions">
                    <h2>Buyer Actions</h2>
                    <form action="{{ route('order.update-status', $order->order_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-primary">Confirm Delivery</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="order-timeline-column">
            <div class="order-panel timeline-panel">
                <h2>Order Timeline</h2>
                <div class="timeline">
                    @foreach($order->statuses as $status)
                        <div class="timeline-event">
                            <div class="timeline-icon {{ $status->status }}">
                                @if($status->status == 'pending')
                                    <i class="fas fa-plus"></i>
                                @elseif($status->status == 'invoice_sent')
                                    <i class="fas fa-file-invoice-dollar"></i>
                                @elseif($status->status == 'paid')
                                    <i class="fas fa-money-bill"></i>
                                @elseif($status->status == 'in_progress')
                                    <i class="fas fa-box"></i>
                                @elseif($status->status == 'shipped')
                                    <i class="fas fa-shipping-fast"></i>
                                @elseif($status->status == 'delivered')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($status->status == 'cancelled')
                                    <i class="fas fa-ban"></i>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <strong>{{ $status->user->username }}</strong>
                                    @if($status->status == 'pending')
                                        created the order
                                    @elseif($status->status == 'cancelled')
                                        cancelled the order
                                    @else
                                        changed the order status to 
                                        @if($status->status == 'invoice_sent')
                                            Invoice Sent
                                        @elseif($status->status == 'paid')
                                            Paid
                                        @elseif($status->status == 'in_progress')
                                            In Progress
                                        @elseif($status->status == 'shipped')
                                            Shipped
                                        @elseif($status->status == 'delivered')
                                            Delivered
                                        @else
                                            {{ ucfirst($status->status) }}
                                        @endif
                                    @endif
                                </div>
                                <div class="timeline-date">
                                    {{ $status->created_at->format('M d, Y H:i A') }}
                                    @if($status->created_at->diffInDays() < 30)
                                        <span class="date-ago">({{ $status->created_at->diffForHumans() }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="order-panel">
                <h2>Messages</h2>
                <div class="message-list">
                    @forelse($order->messages as $message)
                        <div class="message {{ $message->user_id == Auth::id() ? 'sent' : 'received' }}">
                            <div class="message-header">
                                <strong>{{ $message->user->username }}</strong>
                                <span class="message-time">
                                    {{ $message->created_at->format('M d, Y H:i A') }}
                                    @if($message->created_at->diffInDays() < 30)
                                        <span class="date-ago">({{ $message->created_at->diffForHumans() }})</span>
                                    @endif
                                </span>
                            </div>
                            <div class="message-body">
                                {{ $message->message }}
                            </div>
                        </div>
                    @empty
                        <div class="no-messages">
                            No messages yet. Start the conversation!
                        </div>
                    @endforelse
                </div>

                <div class="message-form">
                    <form action="{{ route('order.send-message', $order->order_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="message" class="form-control" placeholder="Write your message here..." rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleCancelForm = document.getElementById('toggle-cancel-form');
        if (toggleCancelForm) {
            toggleCancelForm.addEventListener('click', function() {
                const cancelForm = document.getElementById('cancel-form');
                cancelForm.style.display = cancelForm.style.display === 'none' ? 'block' : 'none';
            });
        }
    });
</script>
@endsection
