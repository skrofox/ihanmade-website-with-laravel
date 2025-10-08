@extends('layouts.web.app')

@section('title', 'Danh mục')

@section('content')

    <div class="lg:mx-32 flex flex-grow">

        <aside class="w-3/12 p-4 rounded-lg m-2">
            <h2 class="text-2xl font-semibold mb-4">Danh Mục</h2>
            <ul class="space-y-2">
                @foreach ($categories as $category)
                    <li><a href="{{ route('categories', ['dm' => $category->slug]) }}"
                            class="text-gray-900 hover:underline">{{ $category->name }}</a></li>
                    <hr>
                @endforeach
            </ul>
        </aside>

        <section class="w-9/12 ml-6">
            <div class="grid lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-2 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-lg">
                        <a href="{{ route('detail', $product->slug) }}">
                            <img src="{{ Storage::url($product->images->first()->url) }}" alt=""
                                class="w-full lg:h-48 sm:h-32 object-cover rounded-t-lg">
                        </a>
                        <h3 class="mt-4 text-lg font-semibold text-truncate text-center">{{ Str::upper($product->name) }}
                        </h3>
                        <p class="text-sm font-bold text-center text-red-600">
                            {{ $product->min_price ? number_format($product->min_price) . 'đ' : 'Liên Hệ' }}</p>
                        <a href="{{ route('detail', $product->slug) }}">
                            <button
                                class="mt-2 bg-gray-800 hover:bg-gray-900 text-white font-monospace px-4 py-2 sm:text-sm md:text-lg w-full">Thêm
                                vào giỏ</button>
                        </a>
                    </div>
                @endforeach
            </div>
            <div>
                {{ $products->links('pagination.page_custom') }}
            </div>
        </section>
    </div>

@endsection
