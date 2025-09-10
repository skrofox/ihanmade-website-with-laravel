@extends('layouts.admin.app')
@section('header', 'Page product')
@section('title', 'product Dashboard')
@section('content')
    <div class="space-y-4">
        <h1 style="margin:0; font-size:1.1rem;">
            <form action="{{ route('product_search') }}" method="get">
                <input type="text" name="keyword" id="keyword" placeholder="name, brand.." value="{{ request('keyword') }}"
                    class="rounded-sm border border-collapse">
                <button type="submit" class="bg-slate-300 hover:bg-slate-500 px-1 py-2 rounded border border-collapse">
                    Search
                </button>
            </form>
        </h1>
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
            <table class="table min-w-[720px] w-full text-left text-lg">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 w-16">Number</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Create at</th>
                        <th class="px-4 py-3">Update at</th>
                        <th class="px-4 py-3 text-center">Detail</th>
                        <th class="px-4 py-3 text-center">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($products as $product)
                        <tr class="hover:bg-slate-500 hover:text-white" id="product-row-{{ $product->id }}">
                            <td scope="row" class="text-center px-4 py-3 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $product->name }}</td>
                            <td class="px-4 py-3">{{ $product->description }}</td>
                            <td class="px-4 py-3">{{ $product->status }}</td>
                            <td class="px-4 py-3 ">
                                {{ $product->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 ">
                                {{ $product->updated_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('product_detail', $product->id) }}" class="hover:underline">Detail</a>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('product_destroy', $product->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn-delete inline-flex items-center gap-1 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium hover:bg-slate-500 active:scale-[.98]">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links('pagination.page_custom') }}
        </div>
    </div>
@endsection
