@extends('layouts.web.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Đặt hàng thành công - Ihandmade.com')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition-colors">Giỏ hàng</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('checkout.index') }}" class="hover:text-blue-600 transition-colors">Thanh toán</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Hoàn thành</span>
        </nav>

        <!-- Success Message -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thành công!</h1>
                <p class="text-lg text-gray-600 mb-6">
                    Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.
                </p>

                <!-- Order Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Thông tin đơn hàng</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Mã đơn hàng:</span>
                                    <span class="font-medium">#{{ $order->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ngày đặt:</span>
                                    <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Trạng thái:</span>
                                    <span class="font-medium text-blue-600">{{ $order->status_text }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tổng tiền:</span>
                                    <span class="font-medium text-red-600">{{ $order->formatted_grand_total }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800 mb-3">Thông tin giao hàng</h3>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-600">Người nhận:</span>
                                    <span class="font-medium block">{{ $order->shippingAddress->full_name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Số điện thoại:</span>
                                    <span class="font-medium block">{{ $order->shippingAddress->phone }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Địa chỉ:</span>
                                    <span class="font-medium block">{{ $order->shippingAddress->line1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white border rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-800 mb-4">Chi tiết đơn hàng</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            @if($item->product->main_image)
                                <img src="{{ Storage::url($item->product->main_image->url) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">{{ $item->name }}</h4>
                                <p class="text-sm text-gray-600">SKU: {{ $item->sku }}</p>
                                <p class="text-sm text-gray-600">Số lượng: {{ $item->qty }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-800">{{ $item->formatted_unit_price }}</p>
                                <p class="text-sm text-gray-600">x {{ $item->qty }}</p>
                                <p class="font-semibold text-red-600">{{ $item->formatted_subtotal }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            @if($order->shipping_total > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-medium">{{ number_format($order->shipping_total, 0, ',', '.') }}đ</span>
                            </div>
                            @endif
                            @if($order->discount_total > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-medium text-green-600">-{{ number_format($order->discount_total, 0, ',', '.') }}đ</span>
                            </div>
                            @endif
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Tổng cộng:</span>
                                <span class="text-red-600">{{ $order->formatted_grand_total }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}" 
                       class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Tiếp tục mua sắm
                    </a>
                    <button onclick="window.print()" 
                            class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                        In đơn hàng
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">Thông tin bổ sung</h4>
                    <p class="text-sm text-blue-700">
                        Bạn sẽ nhận được email xác nhận đơn hàng trong vài phút tới. 
                        Chúng tôi sẽ cập nhật trạng thái đơn hàng qua email.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto hide success message after 5 seconds (if you want to add this feature)
// setTimeout(function() {
//     const successAlert = document.querySelector('.alert-success');
//     if (successAlert) {
//         successAlert.style.display = 'none';
//     }
// }, 5000);
</script>
@endpush
