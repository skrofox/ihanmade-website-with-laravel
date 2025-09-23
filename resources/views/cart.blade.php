@extends('layouts.web.app')

@section('title', 'Giỏ hàng - Ihandmade.com')

@section('content')
    <div class="min-h-screen bg-gray-100 py-8">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-medium">Giỏ hàng</span>
            </nav>

            @if ($cartItems->isEmpty())
                <p>Giỏ hàng trống.</p>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <h1 class="text-2xl font-bold text-gray-800">Giỏ hàng của bạn</h1>
                                <p class="text-gray-600 mt-1">Bạn có <span
                                        class="font-semibold text-blue-600">{{ count($cartItems) }} sản phẩm</span>
                                    trong giỏ hàng</p>
                            </div>

                            <div class="divide-y divide-gray-200">
                                <!-- Cart Item 1 -->

                                @foreach ($cartItems as $item)
                                    <div class="p-6" id="cart-item-{{ $item->id }}">
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('storage/' . ($item->variant->product->main_image ? $item->variant->product->main_image->url : 'default.jpg')) }}"
                                                    alt="Product"
                                                    class="w-full sm:w-24 h-32 sm:h-24 object-cover rounded-lg">
                                            </div>

                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                                            {{ $item->variant->product->name }}
                                                        </h3>
                                                        <p class="text-sm text-gray-600 mb-2">{{ $item->variant->sku }}</p>
                                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                            @foreach ($item->variant->option_value as $key => $value)
                                                                <p>{{ $key }}: {{ $value }}</p><br>
                                                            @endforeach
                                                        </div>
                                                        <p class="text-sm text-gray-600">Giá:
                                                            {{ number_format($item->unit_price_snapshot) }}đ
                                                        </p>
                                                    </div>

                                                    <div class="flex flex-col sm:items-end mt-4 sm:mt-0">
                                                        <!-- Subtotal -->
                                                        <p class="text-lg font-bold text-red-600 mb-2"
                                                            id="subtotal-{{ $item->id }}">
                                                            {{ number_format($item->unit_price_snapshot * $item->quantity) }}đ
                                                        </p>

                                                        <!-- Quantity Controls -->
                                                        <div class="flex items-center border rounded-lg">
                                                            <button class="p-2 hover:bg-gray-100 transition-colors"
                                                                onclick="decreaseQuantity({{ $item->id }})">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M20 12H4"></path>
                                                                </svg>
                                                            </button>
                                                            <input type="number" value="{{ $item->quantity }}"
                                                                min="1"
                                                                class="w-16 text-center border-0 focus:ring-0"
                                                                id="quantity-{{ $item->id }}"
                                                                data-id="{{ $item->id }}">
                                                            <button class="p-2 hover:bg-gray-100 transition-colors"
                                                                onclick="increaseQuantity({{ $item->id }})">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <!-- Continue Shopping -->
                            <div class="p-6 border-t border-gray-200">
                                <a href="{{ route('home') }}"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm sticky top-8">
                            <div class="p-6">
                                <h2 class="text-lg font-bold text-gray-800 mb-4">Tóm tắt đơn hàng</h2>

                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tạm tính:</span>
                                        <span class="font-medium"
                                            id="cart-subtotal">{{ number_format($cart->total) }}đ</span>
                                    </div>
                                    {{-- <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Phí vận chuyển:</span>
                                        <span class="font-medium">30.000đ</span>
                                    </div> --}}
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Giảm giá:</span>
                                        <span class="font-medium text-green-600">00.00đ</span>
                                    </div>
                                    <hr class="my-3">
                                    <hr class="my-3">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Tổng cộng:</span>
                                        <span class="text-red-600"
                                            id="cart-total">{{ number_format($cart->total) }}đ</span>
                                    </div>
                                </div>

                                <!-- Discount Code -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mã giảm giá</label>
                                    <div class="flex">
                                        <input type="text" placeholder="Nhập mã giảm giá"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <button
                                            class="px-4 py-2 bg-gray-800 text-white rounded-r-lg hover:bg-gray-700 transition-colors">
                                            Áp dụng
                                        </button>
                                    </div>
                                </div>

                                <!-- Checkout Button -->
                                <form action="{{ route('checkout.index') }}" method="get">
                                    <button type="submit"
                                        class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors mb-4">
                                        Thanh toán
                                    </button>
                                </form>

                                <!-- Payment Methods -->
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-3">Chấp nhận thanh toán</p>
                                    <div class="flex justify-center space-x-2">
                                        <div
                                            class="w-10 h-6 bg-blue-600 rounded text-white text-xs flex items-center justify-center">
                                            VISA</div>
                                        <div
                                            class="w-10 h-6 bg-red-500 rounded text-white text-xs flex items-center justify-center">
                                            MC</div>
                                        <div
                                            class="w-10 h-6 bg-blue-500 rounded text-white text-xs flex items-center justify-center">
                                            MOMO</div>
                                        <div
                                            class="w-10 h-6 bg-green-600 rounded text-white text-xs flex items-center justify-center">
                                            ATM</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function decreaseQuantity(id) {
            const input = document.getElementById(`quantity-${id}`);
            let currentValue = parseInt(input.value);
            if (currentValue > 1) {
                currentValue -= 1;
                updateQuantity(id, currentValue);
            }
        }

        function increaseQuantity(id) {
            const input = document.getElementById(`quantity-${id}`);
            let currentValue = parseInt(input.value);
            currentValue += 1;
            updateQuantity(id, currentValue);
        }

        function updateTotal() {
            // Calculate total logic here
            console.log('Updating total...');
        }

        // Add smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('button[onclick*="delete"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                        // Add delete logic here
                        const cartItem = this.closest('.p-6');
                        cartItem.style.opacity = '0.5';
                        cartItem.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            cartItem.remove();
                            updateTotal();
                        }, 300);
                    }
                });
            });
        });

        function updateQuantity(id, newQuantity) {
            fetch(`cart/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật lại subtotal & total
                        document.querySelector(`#quantity-${id}`).value = newQuantity;

                        const subtotalEl = document.querySelector(`#subtotal-${id}`);
                        if (subtotalEl) subtotalEl.textContent = data.subtotal.toLocaleString() + 'đ';

                        const totalEl = document.querySelector('#cart-total');
                        if (totalEl) totalEl.textContent = data.cart_total.toLocaleString() + 'đ';

                        // update subtotal của item
                        document.querySelector(`#subtotal-${id}`).textContent =
                            data.subtotal.toLocaleString() + 'đ';

                        // update tổng giỏ hàng
                        document.querySelector('#cart-subtotal').textContent =
                            data.cart_total.toLocaleString() + 'đ';
                        document.querySelector('#cart-total').textContent =
                            data.cart_total.toLocaleString() + 'đ';
                    }
                })
                .catch(err => console.error(err));
        }
    </script>
@endpush
