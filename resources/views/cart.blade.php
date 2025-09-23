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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h1 class="text-2xl font-bold text-gray-800">Giỏ hàng của bạn</h1>
                            <p class="text-gray-600 mt-1">Bạn có <span class="font-semibold text-blue-600">3 sản phẩm</span>
                                trong giỏ hàng</p>
                        </div>

                        <div class="divide-y divide-gray-200">
                            <!-- Cart Item 1 -->
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300"
                                            alt="Product" class="w-full sm:w-24 h-32 sm:h-24 object-cover rounded-lg">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Bàn phím cơ Monka</h3>
                                                <p class="text-sm text-gray-600 mb-2">SKU: MONKAE75</p>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                    <span>Màu: <span class="font-medium">Đen</span></span>
                                                    <span>Kích thước: <span class="font-medium">75%</span></span>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:items-end mt-4 sm:mt-0">
                                                <p class="text-lg font-bold text-red-600 mb-2">999.000đ</p>

                                                <!-- Quantity Controls -->
                                                <div class="flex items-center border rounded-lg">
                                                    <button class="p-2 hover:bg-gray-100 transition-colors"
                                                        onclick="decreaseQuantity(1)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" value="1" min="1"
                                                        class="w-16 text-center border-0 focus:ring-0" id="quantity-1">
                                                    <button class="p-2 hover:bg-gray-100 transition-colors"
                                                        onclick="increaseQuantity(1)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center justify-between mt-4">
                                            <button
                                                class="flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Xóa
                                            </button>
                                            <button
                                                class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                                Yêu thích
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Item 2 -->
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex-shrink-0">
                                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=300"
                                            alt="Product" class="w-full sm:w-24 h-32 sm:h-24 object-cover rounded-lg">
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Áo thun Number One Girl
                                                </h3>
                                                <p class="text-sm text-gray-600 mb-2">SKU: TEE001</p>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                    <span>Màu: <span class="font-medium">Trắng</span></span>
                                                    <span>Size: <span class="font-medium">M</span></span>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:items-end mt-4 sm:mt-0">
                                                <p class="text-lg font-bold text-red-600 mb-2">299.000đ</p>

                                                <div class="flex items-center border rounded-lg">
                                                    <button class="p-2 hover:bg-gray-100 transition-colors"
                                                        onclick="decreaseQuantity(2)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" value="2" min="1"
                                                        class="w-16 text-center border-0 focus:ring-0" id="quantity-2">
                                                    <button class="p-2 hover:bg-gray-100 transition-colors"
                                                        onclick="increaseQuantity(2)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-4">
                                            <button
                                                class="flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Xóa
                                            </button>
                                            <button
                                                class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                                Yêu thích
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Item 3 -->
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex-shrink-0">
                                        <img src="https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=300"
                                            alt="Product" class="w-full sm:w-24 h-32 sm:h-24 object-cover rounded-lg">
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Túi tote canvas</h3>
                                                <p class="text-sm text-gray-600 mb-2">SKU: BAG001</p>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                    <span>Màu: <span class="font-medium">Be</span></span>
                                                    <span>Chất liệu: <span class="font-medium">Canvas</span></span>
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:items-end mt-4 sm:mt-0">
                                                <p class="text-lg font-bold text-red-600 mb-2">199.000đ</p>

                                                <div class="flex items-center border rounded-lg">
                                                    <button class="p-2 hover:bg-gray-100 transition-colors"
                                                        onclick="decreaseQuantity(3)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" value="1" min="1"
                                                        class="w-16 text-center border-0 focus:ring-0" id="quantity-3">
                                                    <button class="p-2 hover:bg-gray-100 transition-colors"
                                                        onclick="increaseQuantity(3)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-4">
                                            <button
                                                class="flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Xóa
                                            </button>
                                            <button
                                                class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                                Yêu thích
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="p-6 border-t border-gray-200">
                            <a href="#"
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
                                    <span class="font-medium">1.597.000đ</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Phí vận chuyển:</span>
                                    <span class="font-medium">30.000đ</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Giảm giá:</span>
                                    <span class="font-medium text-green-600">-50.000đ</span>
                                </div>
                                <hr class="my-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Tổng cộng:</span>
                                    <span class="text-red-600">1.577.000đ</span>
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
                            <button
                                class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors mb-4">
                                Thanh toán
                            </button>

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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function decreaseQuantity(id) {
            const input = document.getElementById(`quantity-${id}`);
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateTotal();
            }
        }

        function increaseQuantity(id) {
            const input = document.getElementById(`quantity-${id}`);
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
            updateTotal();
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
    </script>
@endpush
