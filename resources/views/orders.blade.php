@extends('layouts.app')

@section('title', 'Your Orders')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/orderstyle.css') }}">
@endsection

@section('content')
<div class="orders-container">
    <h1>Your Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="orders-tabs">
        <a href="#purchases" class="tab active" data-tab="purchases">Purchases</a>
        <a href="#sales" class="tab" data-tab="sales">Sales</a>
    </div>

    <div id="purchases" class="tab-content active">
        <h2>Orders You've Purchased</h2>
        
        @if($buyerOrders->isEmpty())
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="{{ route('marketplace') }}" class="btn btn-primary">Shop Marketplace</a>
            </div>
        @else
            <div class="orders-grid">
                @foreach($buyerOrders as $order)
                    <a href="{{ route('order.show', $order->order_id) }}" class="order-card">
                        <div class="order-card-header">
                            <span class="order-number">{{ $order->getOrderNumber() }}</span>
                            <span class="order-status {{ $order->getStatusClass() }}">{{ $order->getStatusLabel() }}</span>
                        </div>
                        <div class="order-card-body">
                            <div class="order-info">
                                <p>Seller: {{ $order->seller->username }}</p>
                                <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
                                <p>Items: {{ $order->items->count() }}</p>
                            </div>
                            <div class="order-total">
                                €{{ number_format($order->total_amount, 2) }}
                            </div>
                        </div>
                        <div class="order-card-items">
                            @foreach($order->items->take(2) as $item)
                                <div class="order-card-item">
                                    <img src="{{ $item->listing->vinyl->cover }}" alt="{{ $item->listing->vinyl->title }}">
                                </div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <div class="order-card-more">+{{ $order->items->count() - 2 }} more</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div id="sales" class="tab-content">
        <h2>Orders You've Sold</h2>
        
        @if($sellerOrders->isEmpty())
            <div class="no-orders">
                <p>You haven't sold any items yet.</p>
                <a href="{{ route('listing.create') }}" class="btn btn-primary">List an Item for Sale</a>
            </div>
        @else
            <div class="orders-grid">
                @foreach($sellerOrders as $order)
                    <a href="{{ route('order.show', $order->order_id) }}" class="order-card">
                        <div class="order-card-header">
                            <span class="order-number">{{ $order->getOrderNumber() }}</span>
                            <span class="order-status {{ $order->getStatusClass() }}">{{ $order->getStatusLabel() }}</span>
                        </div>
                        <div class="order-card-body">
                            <div class="order-info">
                                <p>Buyer: {{ $order->user->username }}</p>
                                <p>Date: {{ $order->created_at->format('M d, Y') }}</p>
                                <p>Items: {{ $order->items->count() }}</p>
                            </div>
                            <div class="order-total">
                                €{{ number_format($order->total_amount, 2) }}
                            </div>
                        </div>
                        <div class="order-card-items">
                            @foreach($order->items->take(2) as $item)
                                <div class="order-card-item">
                                    <img src="{{ $item->listing->vinyl->cover }}" alt="{{ $item->listing->vinyl->title }}">
                                </div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <div class="order-card-more">+{{ $order->items->count() - 2 }} more</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and content
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
                
                // Update URL hash
                window.location.hash = tabId;
            });
        });
        
        // Check for hash in URL
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            const tab = document.querySelector(`.tab[data-tab="${hash}"]`);
            if (tab) {
                tab.click();
            }
        }
    });
</script>
@endsection
