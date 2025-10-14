@extends('layouts.web.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Thanh toán - Ihandmade.com')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
                <span class="text-gray-400">/</span>
                <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition-colors">Giỏ hàng</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-medium">Thanh toán</span>
            </nav>

            <!-- Alert Messages -->
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium">Có lỗi xảy ra:</h3>
                            <ul class="mt-2 text-sm list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-600">Giỏ hàng</span>
                    </div>
                    <div class="w-12 h-0.5 bg-blue-600"></div>
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-blue-600">Thanh toán</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Hoàn thành</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Thông tin khách hàng</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
                                    <input type="text" name="full_name"
                                        value="{{ old('full_name', Auth::user()->name ?? '') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('full_name') border-red-500 @enderror"
                                        placeholder="Nhập họ và tên">
                                    @error('full_name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
                                    <input type="tel" name="phone"
                                        value="{{ old('phone', $user->addresses->first()->phone ?? '') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                        placeholder="Nhập số điện thoại">
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                        placeholder="Nhập email (tùy chọn)">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Địa chỉ giao hàng</h2>
                            <div class="space-y-4 w-full ">
                                <p>
                                    @error('address')
                                        {{ $message }}
                                    @enderror
                                </p>
                                <div class="css_select_div">
                                    <select class="css_select" id="tinh" name="tinh" title="Chọn Tỉnh Thành"
                                        required>
                                        <option value="0">Tỉnh Thành</option>
                                    </select>
                                    <select class="css_select" id="quan" name="quan" title="Chọn Quận Huyện"
                                        required>
                                        <option value="0">Quận Huyện</option>
                                    </select>
                                    <select class="css_select" id="phuong" name="phuong" title="Chọn Phường Xã"
                                        required>
                                        <option value="0">Phường Xã</option>
                                    </select>
                                    <input type="hidden" name="tinh_name" id="tinh_name" value="{{ old('tinh_name') }}">
                                    <input type="hidden" name="quan_name" id="quan_name" value="{{ old('quan_name') }}">
                                    <input type="hidden" name="phuong_name" id="phuong_name"
                                        value="{{ old('phuong_name') }}">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ cụ thể *</label>
                                    <textarea name="address" required rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                        placeholder="Nhập số nhà, tên đường...">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        {{-- <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Phương thức vận chuyển</h2>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" 
                                       name="shipping_method" 
                                       value="standard" 
                                       checked
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-800">Giao hàng tiêu chuẩn</p>
                                            <p class="text-sm text-gray-600">3-5 ngày làm việc</p>
                                        </div>
                                        <span class="font-bold text-gray-800">30.000đ</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" 
                                       name="shipping_method" 
                                       value="express"
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-800">Giao hàng nhanh</p>
                                            <p class="text-sm text-gray-600">1-2 ngày làm việc</p>
                                        </div>
                                        <span class="font-bold text-gray-800">50.000đ</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" 
                                       name="shipping_method" 
                                       value="same_day"
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-800">Giao trong ngày</p>
                                            <p class="text-sm text-gray-600">Chỉ áp dụng nội thành TP.HCM</p>
                                        </div>
                                        <span class="font-bold text-gray-800">80.000đ</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div> --}}

                        <!-- Payment Method -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Phương thức thanh toán</h2>
                            <div class="space-y-3">
                                @error('payment_method')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="payment_method" value="cod" checked
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex items-center">
                                        <div
                                            class="w-10 h-8 bg-green-600 rounded text-white text-xs flex items-center justify-center mr-3">
                                            COD</div>
                                        <div>
                                            <p class="font-medium text-gray-800">Thanh toán khi nhận hàng</p>
                                            <p class="text-sm text-gray-600">Trả tiền mặt khi shipper giao hàng</p>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="payment_method" value="momo"
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex items-center">
                                        <div
                                            class="w-10 h-8 bg-pink-600 rounded text-white text-xs flex items-center justify-center mr-3">
                                            MOMO</div>
                                        <div>
                                            <p class="font-medium text-gray-800">Ví MoMo</p>
                                            <p class="text-sm text-gray-600">Thanh toán qua ứng dụng MoMo</p>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex items-center">
                                        <div
                                            class="w-10 h-8 bg-blue-600 rounded text-white text-xs flex items-center justify-center mr-3">
                                            ATM</div>
                                        <div>
                                            <p class="font-medium text-gray-800">Chuyển khoản ngân hàng</p>
                                            <p class="text-sm text-gray-600">Chuyển khoản qua Internet Banking</p>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="payment_method" value="credit_card"
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex items-center">
                                        <div class="flex space-x-1 mr-3">
                                            <div
                                                class="w-8 h-5 bg-blue-600 rounded text-white text-xs flex items-center justify-center">
                                                VISA</div>
                                            <div
                                                class="w-8 h-5 bg-red-500 rounded text-white text-xs flex items-center justify-center">
                                                MC</div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Thẻ tín dụng/ghi nợ</p>
                                            <p class="text-sm text-gray-600">Visa, Mastercard, JCB</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Ghi chú đơn hàng</h2>
                            <textarea name="notes" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                placeholder="Ghi chú thêm về đơn hàng (tùy chọn)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm sticky top-8">
                            <div class="p-6">
                                <h2 class="text-lg font-bold text-gray-800 mb-6">Đơn hàng của bạn</h2>

                                <!-- Order Items -->
                                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                                    @foreach ($cart->items as $item)
                                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                                            @if ($item->variant->product->main_image)
                                                <img src="{{ Storage::url($item->variant->product->main_image->url) }}"
                                                    alt="{{ $item->variant->product->name }}"
                                                    class="w-12 h-12 object-cover rounded">
                                            @else
                                                <div
                                                    class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">No Image</span>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-800 truncate">
                                                    {{ $item->variant->product->name }}</p>
                                                @if ($item->variant->option_value_text !== 'Không có')
                                                    <p class="text-xs text-gray-600">
                                                        {{ $item->variant->option_value_text }}</p>
                                                @endif
                                                <p class="text-xs text-gray-600">Số lượng: {{ $item->quantity }}</p>
                                            </div>
                                            <p class="text-sm font-bold text-gray-800">
                                                {{ number_format($item->subtotal()) }}đ</p>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Order Total -->
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tạm tính:</span>
                                        <span class="font-medium">{{ number_format($cart->total) }}đ</span>
                                    </div>
                                    {{-- <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Phí vận chuyển:</span>
                                    <span class="font-medium" id="shipping-cost">30.000đ</span>
                                </div> --}}
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Giảm giá:</span>
                                        <span class="font-medium text-green-600">-0đ</span>
                                    </div>
                                    <hr class="my-3">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Tổng cộng:</span>
                                        <span class="text-red-600"
                                            id="final-total">{{ number_format($cart->total) }}đ</span>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-6">
                                    <label class="flex items-start">
                                        <input type="checkbox" name="agree_terms" value="1" required
                                            class="mt-1 w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('agree_terms') border-red-500 @enderror">
                                        <span class="ml-2 text-xs text-gray-600">
                                            Tôi đồng ý với
                                            <a href="#" class="text-blue-600 hover:underline">Điều khoản sử dụng</a>
                                            và
                                            <a href="#" class="text-blue-600 hover:underline">Chính sách bảo mật</a>
                                        </span>
                                    </label>
                                    @error('agree_terms')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Place Order Button -->
                                <button type="submit"
                                    class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors mb-4">
                                    Đặt hàng ngay
                                </button>

                                <!-- Back to Cart -->
                                <a href="{{ route('cart.index') }}"
                                    class="block w-full text-center py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">
                                    ← Quay lại giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://esgoo.net/scripts/jquery.js"></script>
    <script>
        $(document).ready(function() {
            //Lấy tỉnh thành
            $.getJSON('https://esgoo.net/api-tinhthanh/1/0.htm', function(data_tinh) {
                if (data_tinh.error == 0) {
                    $.each(data_tinh.data, function(key_tinh, val_tinh) {
                        $("#tinh").append('<option value="' + val_tinh.id + '">' + val_tinh
                            .full_name + '</option>');
                    });
                    $("#tinh").change(function(e) {
                        var idtinh = $(this).val();
                        //Lấy quận huyện
                        $.getJSON('https://esgoo.net/api-tinhthanh/2/' + idtinh + '.htm', function(
                            data_quan) {
                            if (data_quan.error == 0) {
                                $("#quan").html('<option value="0">Quận Huyện</option>');
                                $("#phuong").html('<option value="0">Phường Xã</option>');
                                $.each(data_quan.data, function(key_quan, val_quan) {
                                    $("#quan").append('<option value="' + val_quan
                                        .id + '">' + val_quan.full_name +
                                        '</option>');
                                });
                                //Lấy phường xã  
                                $("#quan").change(function(e) {
                                    var idquan = $(this).val();
                                    $.getJSON('https://esgoo.net/api-tinhthanh/3/' +
                                        idquan + '.htm',
                                        function(data_phuong) {
                                            if (data_phuong.error == 0) {
                                                $("#phuong").html(
                                                    '<option value="0">Phường Xã</option>'
                                                );
                                                $.each(data_phuong.data,
                                                    function(key_phuong,
                                                        val_phuong) {
                                                        $("#phuong").append(
                                                            '<option value="' +
                                                            val_phuong
                                                            .id + '">' +
                                                            val_phuong
                                                            .full_name +
                                                            '</option>');
                                                    });
                                            }
                                        });
                                });

                            }
                        });
                    });

                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
            const shippingCostElement = document.getElementById('shipping-cost');
            const finalTotalElement = document.getElementById('final-total');

            const baseTotal = {{ $cart->total }};
            const discount = 0;

            // Update shipping cost when method changes (currently disabled)
            // shippingMethods.forEach(method => {
            //     method.addEventListener('change', function() {
            //         let shippingCost = 0;
            //         const finalTotal = baseTotal + shippingCost - discount;
            //         finalTotalElement.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ';
            //     });
            // });

            // Form validation
            const form = document.getElementById('checkout-form');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang xử lý...';
            });

            // Province/District/Ward cascading
            const provinceSelect = document.querySelector('select[name="province"]');
            const districtSelect = document.querySelector('select[name="district"]');
            const wardSelect = document.querySelector('select[name="ward"]');

            provinceSelect.addEventListener('change', function() {
                // Reset district and ward
                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                if (this.value === 'ho-chi-minh') {
                    districtSelect.innerHTML = `
                <option value="">Chọn quận/huyện</option>
                <option value="quan-1">Quận 1</option>
                <option value="quan-3">Quận 3</option>
                <option value="quan-7">Quận 7</option>
                <option value="thu-duc">Thủ Đức</option>
            `;
                }
                // Add more provinces as needed
            });

            districtSelect.addEventListener('change', function() {
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                if (this.value === 'quan-1') {
                    wardSelect.innerHTML = `
                <option value="">Chọn phường/xã</option>
                <option value="phuong-ben-thanh">Phường Bến Thành</option>
                <option value="phuong-da-kao">Phường Đa Kao</option>
                <option value="phuong-nguyen-thai-binh">Phường Nguyễn Thái Bình</option>
            `;
                }
                // Add more districts as needed
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tinh = document.getElementById('tinh');
            const quan = document.getElementById('quan');
            const phuong = document.getElementById('phuong');

            // giá trị trước (giữ khi validate lỗi)
            const oldTinh = "{{ old('tinh', $user->addresses->first()->province_id ?? '') }}";
            const oldQuan = "{{ old('quan', $user->addresses->first()->district_id ?? '') }}";
            const oldPhuong = "{{ old('phuong', $user->addresses->first()->ward_id ?? '') }}";

            function fetchJson(url) {
                return fetch(url, {
                    cache: 'no-cache'
                }).then(r => r.json());
            }

            // nạp tỉnh
            fetchJson('https://esgoo.net/api-tinhthanh/1/0.htm')
                .then(data => {
                    if (data && data.error === 0) {
                        data.data.forEach(p => {
                            const o = document.createElement('option');
                            o.value = p.id;
                            o.textContent = p.full_name;
                            if (String(p.id) === String(oldTinh)) o.selected = true;
                            tinh.appendChild(o);
                        });
                        if (oldTinh) tinh.dispatchEvent(new Event('change'));
                    }
                }).catch(() => {});

            // khi chọn tỉnh -> load quận
            tinh.addEventListener('change', function() {
                const idtinh = this.value || 0;
                quan.innerHTML = '<option value="0">Quận Huyện</option>';
                phuong.innerHTML = '<option value="0">Phường Xã</option>';
                if (!idtinh || idtinh === '0') return;
                fetchJson('https://esgoo.net/api-tinhthanh/2/' + idtinh + '.htm')
                    .then(data => {
                        if (data && data.error === 0) {
                            data.data.forEach(q => {
                                const o = document.createElement('option');
                                o.value = q.id;
                                o.textContent = q.full_name;
                                if (String(q.id) === String(oldQuan)) o.selected = true;
                                quan.appendChild(o);
                            });
                            if (oldQuan) quan.dispatchEvent(new Event('change'));
                        }
                    }).catch(() => {});
            });

            // khi chọn quận -> load phường
            quan.addEventListener('change', function() {
                const idquan = this.value || 0;
                phuong.innerHTML = '<option value="0">Phường Xã</option>';
                if (!idquan || idquan === '0') return;
                fetchJson('https://esgoo.net/api-tinhthanh/3/' + idquan + '.htm')
                    .then(data => {
                        if (data && data.error === 0) {
                            data.data.forEach(w => {
                                const o = document.createElement('option');
                                o.value = w.id;
                                o.textContent = w.full_name;
                                if (String(w.id) === String(oldPhuong)) o.selected = true;
                                phuong.appendChild(o);
                            });
                        }
                    }).catch(() => {});
            });

            // Trước khi submit, điền tên tỉnh/quận/phường vào hidden inputs
            const form = document.getElementById('checkout-form');
            form.addEventListener('submit', function(e) {
                const tName = tinh.selectedOptions[0]?.text || '';
                const qName = quan.selectedOptions[0]?.text || '';
                const pName = phuong.selectedOptions[0]?.text || '';
                document.getElementById('tinh_name').value = tName;
                document.getElementById('quan_name').value = qName;
                document.getElementById('phuong_name').value = pName;
                // tiếp tục xử lý validation/loading như hiện tại...
            });
        });
    </script>
@endpush
