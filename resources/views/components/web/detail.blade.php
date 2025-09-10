@extends('layouts.web.app')
@section('content')
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="text-sm breadcrumbs mb-6">
            <div class="flex items-center space-x-2 text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
                <span>›</span>
                @foreach ($product->categories as $category)
                    <a href="#" class="hover:text-blue-600">{{ $category->name }}</a>
                    <span>›</span>
                @endforeach
                {{-- <span>›</span> --}}
                <a href="#" class="hover:text-blue-600">{{ $product->name }}</a>
            </div>
        </nav>

        <!-- Product Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="bg-gray-300 rounded-lg aspect-square w-full flex items-center justify-center">
                    {{-- <span class="text-gray-500">Main Product Image</span> --}}
                    <img src="{{ asset('storage/' . ($product->main_image ? $product->main_image->url : 'default.jpg')) }}"
                        alt="{{ $product->main_image ? $product->main_image->alt : $product->name }}"
                        class="w-full h-full object-contain">
                </div>
                <!-- Thumbnail Images -->
                <div class="grid grid-cols-5 gap-2">
                    @foreach ($product->images as $image)
                    <div class="bg-gray-300 rounded aspect-square flex items-center justify-center">
                        {{-- <span class="text-xs text-gray-500">1</span> --}}
                        <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $image->alt }}" class="w-full h-full object-contain">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold mb-2">{{ $product->name }}</h1>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium">SKU:</span> MONKA-E75</p>
                        <p>
                            <span class="font-medium">BRAND:</span> {{ $product->brand ?? 'No Information' }}
                        </p>
                    </div>
                </div>

                <div class="text-3xl font-bold text-red-600">
                    999.000đ
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="font-medium mb-2">Tình Trạng: <span class="text-green-600">Còn hàng</span></p>
                    </div>

                    <div>
                        <p class="font-medium mb-2">Màu:</p>
                        <div class="flex space-x-2">
                            <div
                                class="w-8 h-8 bg-gray-400 rounded border-2 border-gray-300 cursor-pointer hover:border-blue-500">
                            </div>
                            <div
                                class="w-8 h-8 bg-gray-500 rounded border-2 border-gray-300 cursor-pointer hover:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <p class="font-medium">Số Lượng:</p>
                        <div class="flex items-center border rounded">
                            <button class="px-3 py-1 hover:bg-gray-100">-</button>
                            <input type="number" value="1" min="1"
                                class="w-12 text-center border-none focus:outline-none">
                            <button class="px-3 py-1 hover:bg-gray-100">+</button>
                        </div>
                    </div>
                    <button
                        class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                        THÊM VÀO GIỎ HÀNG
                    </button>
                    <button
                        class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                        MUA NGAY
                        <div class="text-xs opacity-80">GIAO HÀNG TOÀN QUỐC</div>
                    </button>

                    <div class="text-sm text-gray-600">
                        <p>Gọi đặt mua: <a href="tel:0909727772" class="text-blue-600 hover:underline">0909727772</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mb-12">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8">
                    <button class="py-2 px-1 border-b-2 border-blue-600 text-blue-600 font-medium">
                        Mô Tả
                    </button>
                    <button class="py-2 px-1 text-gray-500 hover:text-gray-700">
                        Đánh giá sản phẩm
                    </button>
                </nav>
            </div>
            <div class="mt-6">
                <div class="bg-gray-300 h-32 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Product Description Content</span>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section>
            <h2 class="text-2xl font-bold text-center mb-8">SẢN PHẨM LIÊN QUAN</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gray-300 aspect-square flex items-center justify-center">
                        <span class="text-gray-500">Product Image</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium mb-2">Sản phẩm mới 1</h3>
                        <p class="text-red-600 font-bold">1.500.000đ</p>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gray-300 aspect-square flex items-center justify-center">
                        <span class="text-gray-500">Product Image</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium mb-2">Sản phẩm mới 1</h3>
                        <p class="text-red-600 font-bold">1.500.000đ</p>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gray-300 aspect-square flex items-center justify-center">
                        <span class="text-gray-500">Product Image</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium mb-2">Sản phẩm mới 1</h3>
                        <p class="text-red-600 font-bold">1.500.000đ</p>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gray-300 aspect-square flex items-center justify-center">
                        <span class="text-gray-500">Product Image</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium mb-2">Sản phẩm mới 1</h3>
                        <p class="text-red-600 font-bold">1.500.000đ</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition-colors">
                    XEM TẤT CẢ SẢN PHẨM LIÊN QUAN
                </button>
            </div>
        </section>

        <script>
            // Simple quantity control
            document.addEventListener('DOMContentLoaded', function() {
                const minusBtn = document.querySelector('button:first-of-type');
                const plusBtn = document.querySelector('button:last-of-type');
                const quantityInput = document.querySelector('input[type="number"]');

                minusBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                });

                plusBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    quantityInput.value = currentValue + 1;
                });

                // Color selection
                document.querySelectorAll('.w-8.h-8').forEach(color => {
                    color.addEventListener('click', function() {
                        // Remove active class from all colors
                        document.querySelectorAll('.w-8.h-8').forEach(c => c.classList.remove(
                            'border-blue-500'));
                        // Add active class to clicked color
                        this.classList.add('border-blue-500');
                    });
                });

                // Tab switching
                document.querySelectorAll('nav button').forEach(tab => {
                    tab.addEventListener('click', function() {
                        // Remove active classes
                        document.querySelectorAll('nav button').forEach(t => {
                            t.classList.remove('border-blue-600', 'text-blue-600');
                            t.classList.add('text-gray-500');
                        });
                        // Add active class to clicked tab
                        this.classList.add('border-blue-600', 'text-blue-600');
                        this.classList.remove('text-gray-500');
                    });
                });
            });
        </script>
    </main>
@endsection
