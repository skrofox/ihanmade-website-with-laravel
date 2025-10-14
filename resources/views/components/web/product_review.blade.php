<div class="container mx-auto py-12 px-4 lg:px-8">
    <section>
        <h2 class="text-3xl font-bold text-center mb-8">SẢN PHẨM MỚI</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Product Card 1 -->
            @foreach ($newProducts as $product)
                <a href="{{ route('detail', $product->slug) }}">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-gray-300 aspect-square flex items-center justify-center">
                            {{-- <span class="text-gray-500">Product Image</span> --}}
                            <img src="{{ asset('storage/' . ($product->main_image ? $product->main_image->url : 'default.jpg')) }}"
                                alt="{{ $product->main_image ? $product->main_image->alt : $product->name }}"
                                class="w-full h-full object-contain">
                        </div>
                        <div class="p-4 min-h-32">
                            <h3 class="font-medium mb-2 line-clamp-2">{{ $product->name }}</h3>
                            @if ($product->min_price)
                                <p class="text-red-600 font-bold">{{ number_format($product->min_price) }}</p>
                            @else
                                <p class="text-red-600 font-bold">Liên Hệ</p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ route('categories') }}"
                class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition-colors">
                XEM TẤT CẢ SẢN PHẨM MỚI
            </a>
        </div>
    </section>

</div>
@if ($productshandmade->isEmpty() == false)
    <div class="container mx-auto py-12 px-4 lg:px-8">
        <section>
            <h2 class="text-3xl font-bold text-center mb-8">BỘ SƯU TẬP THỦ CÔNG</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Product Card 1 -->
                @foreach ($productshandmade as $product)
                    <a href="{{ route('detail', $product->slug) }}">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="bg-gray-300 aspect-square flex items-center justify-center">
                                {{-- <span class="text-gray-500">Product Image</span> --}}
                                <img src="{{ asset('storage/' . ($product->main_image ? $product->main_image->url : 'default.jpg')) }}"
                                    alt="{{ $product->main_image ? $product->main_image->alt : $product->name }}"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium mb-2 line-clamp-1">{{ $product->name }}</h3>
                                @if ($product->min_price)
                                    <p class="text-red-600 font-bold">{{ number_format($product->min_price) }}</p>
                                @else
                                    <p class="text-red-600 font-bold">Liên Hệ</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center">
                <a href="{{ route('categories', ['dm' => $productshandmade->first()->categories->first()->slug]) }}"
                    type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition-colors">
                    XEM TẤT CẢ
                </a>
            </div>

        </section>

    </div>
@endif
