@extends('layouts.web.app')

@section('title', 'Search')

@section('content')
    @include('components.web.slide')
    <div class="bg-slate-200">
        <div class="container mx-auto py-12 px-4 lg:px-8">
            <section>
                <h2 class="text-3xl font-bold text-center mb-8">Kết quả tìm kiếm cho "{{ $query }}"</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Product Card 1 -->
                    @foreach ($products as $product)
                        <a href="{{ route('detail', $product->slug) }}">
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                <div class="bg-gray-300 aspect-square flex items-center justify-center">
                                    {{-- <span class="text-gray-500">Product Image</span> --}}
                                    <img src="{{ asset('storage/' . ($product->main_image ? $product->main_image->url : 'default.jpg')) }}"
                                        alt="{{ $product->main_image ? $product->main_image->alt : $product->name }}"
                                        class="w-full h-full object-contain">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-medium mb-2">{{ $product->name }}</h3>
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
            </section>

        </div>

    </div>
@endsection
