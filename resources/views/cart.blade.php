@extends('layouts.app')

@section('title', 'Your Cart | Vinylopia')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/cartstyle.css') }}">
@endsection

@section('content')
    <div class="cart-container">
        <div class="cart-navigation">
            <div class="cart-tabs">
                <a href="{{ route('cart') }}" class="active">Cart</a>
                <a href="{{ route('orders') }}">Order History</a>
            </div>
            
            <a href="{{ route('marketplace') }}" class="continue-shopping">
                <span>Continue Shopping</span>
            </a>
        </div>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if(!isset($cart) || $cart->items->isEmpty())
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="{{ route('marketplace') }}" class="browse-button">Browse Marketplace</a>
            </div>
        @else
            <div class="cart-summary">
                <p>You have {{ $cart->items->count() }} {{ $cart->items->count() == 1 ? 'item' : 'items' }} in your cart from {{ $itemsBySeller->count() }} {{ $itemsBySeller->count() == 1 ? 'seller' : 'sellers' }}.</p>
            </div>

            @php
                $totalItems = 0;
                $totalSubtotal = 0;
                $totalShipping = 0;
            @endphp

            <div class="cart-grid">
                @foreach($itemsBySeller as $sellerIndex => $sellerItems)
                    @php
                        $itemCount = count($sellerItems['items']);
                        $subtotal = $sellerItems['subtotal'];
                        $shipping = $sellerItems['shipping'];
                        $orderTotal = $subtotal + $shipping;
                        
                        $totalItems += $itemCount;
                        $totalSubtotal += $subtotal;
                        $totalShipping += $shipping;
                    @endphp
                    <div class="seller-cart">
                        <div class="seller-info">
                            <p>Order from <strong>{{ $sellerItems['seller']->username }}</strong></p>
                        </div>

                        <div class="cart-items">
                            @foreach($sellerItems['items'] as $item)
                                <div class="cart-item">
                                    <div class="item-image">
                                        <img src="{{ $item->listing->vinyl->cover }}" alt="{{ $item->listing->vinyl->title }}">
                                    </div>
                                    <div class="item-details">
                                        <h3>
                                            <a href="{{ route('vinyl.release', $item->listing->vinyl->vinyl_id) }}">
                                                {{ $item->listing->vinyl->artist }} - {{ $item->listing->vinyl->title }}
                                            </a>
                                        </h3>
                                        <p>Media: {{ $item->listing->vinyl_condition }}</p>
                                        <p>Sleeve: {{ $item->listing->cover_condition }}</p>
                                    </div>
                                    <div class="item-price">
                                        €{{ number_format($item->price, 2) }}
                                    </div>
                                    <div class="item-actions">
                                        <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="remove-btn">remove</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="shipping-section">
                            <label for="shipping-{{ $sellerIndex }}">Shipping:</label>
                            <select id="shipping-{{ $sellerIndex }}" class="shipping-selector" 
                                    data-seller-index="{{ $sellerIndex }}"
                                    data-subtotal="{{ $subtotal }}"
                                    data-standard="{{ $sellerItems['shipping'] }}"
                                    data-expedited="{{ $sellerItems['shipping'] * 1.5 }}"
                                    data-express="{{ $sellerItems['shipping'] * 2 }}">
                                <option value="standard" data-price="{{ $sellerItems['shipping'] }}">Standard - DPD Classic (€{{ number_format($sellerItems['shipping'], 2) }})</option>
                                <option value="expedited" data-price="{{ $sellerItems['shipping'] * 1.5 }}">Expedited - DHL Parcel Express (€{{ number_format($sellerItems['shipping'] * 1.5, 2) }})</option>
                                <option value="express" data-price="{{ $sellerItems['shipping'] * 2 }}">Express - UPS Express Saver (€{{ number_format($sellerItems['shipping'] * 2, 2) }})</option>
                            </select>
                            <p class="delivery-estimate">Delivery in 3-7 business days</p>
                        </div>
                        <div class="shipping-address-section">
                            <h3>Shipping Address</h3>
                            <textarea name="shipping_address-{{ $sellerIndex }}" class="shipping-address-textarea" rows="3" required>{{ Auth::user()->address }}</textarea>
                        </div>

                        <div class="order-details">
                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Items ({{ $itemCount }}):</span>
                                    <span>€{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="summary-row shipping-cost" data-seller-index="{{ $sellerIndex }}">
                                    <span>Shipping:</span>
                                    <span>€<span class="shipping-value">{{ number_format($shipping, 2) }}</span></span>
                                </div>
                                
                                <div class="summary-row total seller-total" data-seller-index="{{ $sellerIndex }}">
                                    <span>Order Total:</span>
                                    <span>€<span class="seller-total-value">{{ number_format($orderTotal, 2) }}</span></span>
                                </div>
                            </div>
                            
                            <div class="payment-section">
                                <h3>Payment Method</h3>
                                <div class="payment-methods">
                                    <div class="payment-method">
                                        <input type="radio" id="paypal-{{ $sellerIndex }}" name="payment-{{ $sellerIndex }}" checked>
                                        <label for="paypal-{{ $sellerIndex }}" class="payment-label paypal">PayPal</label>
                                    </div>
                                    <div class="payment-method">
                                        <input type="radio" id="credit-{{ $sellerIndex }}" name="payment-{{ $sellerIndex }}">
                                        <label for="credit-{{ $sellerIndex }}" class="payment-label credit">Credit Card</label>
                                    </div>
                                </div>
                                
                                <div class="terms">
                                    <label>
                                        <input type="checkbox" required>
                                        I agree to Vinylopia's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                    </label>
                                </div>
                                
                                <form action="{{ route('checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="seller_id" value="{{ $sellerItems['seller']->user_id }}">
                                    <input type="hidden" name="shipping_method" class="shipping-method-input" value="standard">
                                    <input type="hidden" name="shipping_cost" class="shipping-cost-input" value="{{ $shipping }}">
                                    <input type="hidden" name="payment_method" value="paypal" class="payment-method-input">
                                    <input type="hidden" name="shipping_address" class="shipping-address-input">
                                    
                                    <button type="submit" class="checkout-button" data-seller-index="{{ $sellerIndex }}">
                                        <span class="lock-icon">🔒</span>
                                        Place Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const shippingSelectors = document.querySelectorAll('.shipping-selector');
                    const checkoutButtons = document.querySelectorAll('.checkout-button');
                    
                    shippingSelectors.forEach(selector => {
                        selector.addEventListener('change', function() {
                            updateSellerTotal(this);
                            
                            // Update the hidden shipping method and cost inputs
                            const sellerIndex = this.getAttribute('data-seller-index');
                            const selectedOption = this.options[this.selectedIndex];
                            const shippingMethod = this.value; // standard, expedited, or express
                            const shippingPrice = selectedOption.getAttribute('data-price');
                            
                            const form = document.querySelector(`.checkout-button[data-seller-index="${sellerIndex}"]`).closest('form');
                            form.querySelector('.shipping-method-input').value = shippingMethod;
                            form.querySelector('.shipping-cost-input').value = shippingPrice;
                        });
                    });
                    
                    // Handle payment method changes
                    document.querySelectorAll('input[type="radio"][name^="payment_method-"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const sellerIndex = this.id.split('-')[1];
                            const paymentMethod = this.value;
                            
                            const form = document.querySelector(`.checkout-button[data-seller-index="${sellerIndex}"]`).closest('form');
                            form.querySelector('.payment-method-input').value = paymentMethod;
                        });
                    });
                    
                    checkoutButtons.forEach(button => {
                        button.closest('form').addEventListener('submit', function(e) {
                            e.preventDefault(); // Prevent form from submitting immediately
                            
                            const sellerIndex = button.getAttribute('data-seller-index');
                            const addressTextarea = document.querySelector(`textarea[name="shipping_address-${sellerIndex}"]`);
                            
                            // Validate shipping address
                            if (!addressTextarea || addressTextarea.value.trim() === '') {
                                alert('Please enter a shipping address');
                                return;
                            }
                            
                            // Check terms checkbox
                            const termsCheckbox = this.closest('.seller-cart').querySelector('.terms input[type="checkbox"]');
                            if (!termsCheckbox || !termsCheckbox.checked) {
                                alert('Please agree to the Terms of Service and Privacy Policy');
                                return;
                            }
                            
                            // Set the shipping address in the form's hidden field
                            this.querySelector('.shipping-address-input').value = addressTextarea.value;
                            
                            // Add a loading state to prevent double submission
                            button.disabled = true;
                            button.textContent = 'Processing...';
                            
                            // Now submit the form
                            this.submit();
                        });
                    });
                    
                    function updateSellerTotal(selector) {
                        const sellerIndex = selector.getAttribute('data-seller-index');
                        const selectedOption = selector.options[selector.selectedIndex];
                        const shippingPrice = parseFloat(selectedOption.getAttribute('data-price'));
                        const subtotal = parseFloat(selector.getAttribute('data-subtotal'));
                        
                        // Update shipping display for this seller
                        const shippingDisplay = document.querySelector(`.shipping-cost[data-seller-index="${sellerIndex}"] .shipping-value`);
                        shippingDisplay.textContent = shippingPrice.toFixed(2);
                        
                        // Calculate total (subtotal + shipping)
                        const sellerTotal = subtotal + shippingPrice;
                        
                        // Update seller total
                        const sellerTotalDisplay = document.querySelector(`.seller-total[data-seller-index="${sellerIndex}"] .seller-total-value`);
                        sellerTotalDisplay.textContent = sellerTotal.toFixed(2);
                    }
                });
            </script>
        @endif
    </div>
@endsection