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
                    <img id="main-image"
                        src="{{ asset('storage/' . ($product->main_image ? $product->main_image->url : 'default.jpg')) }}"
                        alt="{{ $product->main_image ? $product->main_image->alt : $product->name }}"
                        class="w-full h-full object-contain">
                </div>
                <!-- Thumbnail Images -->
                {{-- <div class="grid grid-cols-5 gap-2">
                    @foreach ($product->images as $image)
                        <div class="bg-gray-300 rounded aspect-square flex items-center justify-center">
                            <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $image->alt }}"
                                class="w-full h-full object-contain">
                        </div>
                    @endforeach
                </div> --}}
                <div class="grid grid-cols-5 gap-2">
                    @foreach ($product->images as $image)
                        <div class="bg-gray-300 rounded aspect-square flex items-center justify-center">
                            <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $image->alt }}"
                                class="thumbnail-image w-full h-full object-contain cursor-pointer"
                                data-image="{{ asset('storage/' . $image->url) }}" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold mb-2">{{ $product->name }}</h1>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p>
                            <span class="font-medium">BRAND:</span> {{ $product->brand ?? 'No Information' }}
                        </p>
                    </div>
                </div>
                {{-- <div>
                    <div class="text-md text-gray-600 space-y-1">
                        <p>
                            <span class="font-medium">Mô tả:</span> {{ $product->description ?? 'No Information' }}
                        </p>
                    </div>
                </div> --}}

                <div class="text-2xl font-bold text-red-600" id="variant-price">
                    @if ($product->min_price)
                        {{ number_format($product->min_price) }} VND - {{ number_format($product->max_price) }} VND
                    @else
                        Liên Hệ
                    @endif
                </div>

                <div class="space-y-4">
                    <div id="variant-info">
                        <p class="font-medium mb-2">
                            Tình Trạng:
                            @if ($product->total_stock > 0)
                                <span class="text-green-600" id="variant-status">Còn hàng</span>
                            @else
                                <span class="text-red-600" id="variant-status">Hết hàng</span>
                            @endif
                        </p>
                    </div>


                    @if ($product->total_stock > 0)
                        <div>
                            <p class="font-medium mb-2">Loại:</p>
                            <div class="flex space-x-2">
                                @foreach ($product->variants as $variant)
                                    <div class="variant-option border border-collapse rounded-sm border-slate-400 hover:cursor-pointer hover:bg-slate-200 transition-colors varitant_{{ $variant->id }}"
                                        data-variant-id="{{ $variant->id }}">
                                        <p class="px-4 py-2">
                                            {{ implode(' - ', array_values($variant->option_value)) }}
                                            {{-- Debug: Hiển thị stock --}}
                                            <span class="text-xs text-gray-500">
                                                (Tồn: {{ $variant->stockItems->sum('on_hand') }})
                                            </span>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="quantity-input-container" class="flex items-center space-x-4">
                            <p class="font-medium">Số Lượng:</p>
                            <div class="flex items-center border rounded">
                                <button class="px-3 py-1 hover:bg-gray-100" id="decrement">-</button>
                                <input type="number" value="1" min="1" max=""
                                    class="w-12 text-center border-none focus:outline-none" id="quantity-input">
                                <button class="px-3 py-1 hover:bg-gray-100" id="increment">+</button>
                            </div>
                        </div>
                    @endif

                    <button id="add-to-cart"
                        class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                        THÊM VÀO GIỎ HÀNG
                    </button>
                    @if ($product->total_stock > 0)
                        <button id="buy-now"
                            class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                            MUA NGAY
                            <div class="text-xs opacity-80">GIAO HÀNG TOÀN QUỐC</div>
                        </button>
                    @else
                        <button id="buy-now"
                            class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors opacity-50 cursor-not-allowed"
                            disabled="">
                            MUA NGAY
                            <div class="text-xs opacity-80">GIAO HÀNG TOÀN QUỐC</div>
                        </button>
                    @endif

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

    </main>

    @push('scripts')
        <script>
            //fetch variant cho thằng product
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.variant-option').forEach(el => {
                    el.addEventListener('click', function() {
                        let variantId = this.dataset.variantId;

                        fetch(`/product/variant/${variantId}`)
                            .then(res => res.json())
                            .then(data => {
                                //update GIA
                                document.getElementById('variant-price').innerText = data.price;

                                const statusElement = document.getElementById('variant-status');
                                statusElement.innerText = data.variant_status;

                                if (data.variant_status === 'Hết hàng') {
                                    statusElement.classList.remove('text-green-600');
                                    statusElement.classList.add('text-red-600');

                                    // Vô hiệu hóa nút
                                    // document.getElementById('add-to-cart').disabled = true;
                                    document.getElementById('buy-now').disabled = true;

                                    document.getElementById('quantity-input-container').classList
                                        .add('hidden');

                                    // Thêm style để phản ánh đã disabled (nếu muốn)
                                    // document.getElementById('add-to-cart').classList.add(
                                    //     'opacity-50', 'cursor-not-allowed');
                                    document.getElementById('buy-now').classList.add('opacity-50',
                                        'cursor-not-allowed');

                                } else {
                                    statusElement.classList.remove('text-red-600');
                                    statusElement.classList.add('text-green-600');

                                    // Bật lại nút
                                    // document.getElementById('add-to-cart').disabled = false;
                                    document.getElementById('buy-now').disabled = false;

                                    document.getElementById('quantity-input-container').classList
                                        .remove('hidden');

                                    // document.getElementById('add-to-cart').classList.remove(
                                    //     'opacity-50', 'cursor-not-allowed');
                                    document.getElementById('buy-now').classList.remove(
                                        'opacity-50', 'cursor-not-allowed');
                                }

                                //add class
                                document.querySelectorAll('.variant-option').forEach(item => {
                                    item.classList.remove('bg-slate-300');
                                });

                                // Thêm class vào phần tử đã chọn
                                this.classList.add('bg-slate-300');
                                // this.classList.add('active');
                                // document.getElementById('variant-status').innerText = data.status;

                                // document.querySelector('h1').innerText = data.name;

                                // if (data.images.length > 0) {
                                //     document.querySelector('.main-product-image').src = data.images[0];
                                // }
                            });
                    });
                });
            });

            //làm mấy cái nút tăng, giảm số lượng
            document.addEventListener('DOMContentLoaded', () => {
                const quantityInput = document.getElementById('quantity-input');
                const incrementButton = document.getElementById('increment');
                const decrementButton = document.getElementById('decrement');

                //khi an nut +
                incrementButton.addEventListener('click', () => {
                    let currentValue = parseInt(quantityInput.value);
                    let maxValue = quantityInput.getAttribute('max');

                    if (maxValue && currentValue < parseInt(maxValue)) {
                        quantityInput.value = currentValue + 1;
                    } else if (!maxValue) {
                        quantityInput.value = currentValue + 1;
                    }
                });
                decrementButton.addEventListener('click', () => {
                    let currentValue = parseInt(quantityInput.value);
                    let minValue = quantityInput.getAttribute('min');

                    // Kiểm tra xem giá trị hiện tại có lớn hơn min không (nếu có)
                    if (minValue && currentValue > parseInt(minValue)) {
                        quantityInput.value = currentValue - 1;
                    } else if (!minValue) {
                        quantityInput.value = currentValue - 1;
                    }
                });
                quantityInput.addEventListener('change', () => {
                    let value = parseInt(quantityInput.value);
                    let minValue = quantityInput.getAttribute('min');
                    let maxValue = quantityInput.getAttribute('max');

                    // Đảm bảo giá trị trong input hợp lệ và nằm trong giới hạn min và max
                    if (minValue && value < parseInt(minValue)) {
                        quantityInput.value = minValue;
                    } else if (maxValue && value > parseInt(maxValue)) {
                        quantityInput.value = maxValue;
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                const thumbnailImages = document.querySelectorAll('.thumbnail-image');

                thumbnailImages.forEach(image => {
                    image.addEventListener('click', function() {
                        const newImageSrc = this.getAttribute('data-image');
                        document.getElementById('main-image').src = newImageSrc;
                    });
                });
            });
        </script>
    @endpush
@endsection
