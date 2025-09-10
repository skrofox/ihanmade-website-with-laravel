@extends('layouts.admin.app')
@section('header', 'Thùng rác Stock Items')
@section('title', 'Thùng rác Stock Items')
@section('content')
    <div class="space-y-4">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Thùng rác Stock Items</h1>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('stock_index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Bảng hiển thị stock items đã xóa -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                STT
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kho hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tồn kho
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày xóa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stockItems as $stockItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $stockItem->variant->product->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            SKU: {{ $stockItem->variant->sku }}
                                        </div>
                                        @if($stockItem->variant->barcode)
                                            <div class="text-xs text-gray-400">
                                                Barcode: {{ $stockItem->variant->barcode }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $stockItem->warehouse->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $stockItem->warehouse->code }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($stockItem->on_hand) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stockItem->deleted_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <form action="{{ route('stock_restore', $stockItem->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900"
                                                    onclick="return confirm('Bạn có chắc muốn khôi phục stock item này?')">
                                                Khôi phục
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('stock_force_delete', $stockItem->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn stock item này? Hành động này không thể hoàn tác!')">
                                                Xóa vĩnh viễn
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Không có stock items nào trong thùng rác
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Phân trang -->
        @if($stockItems->hasPages())
            <div class="mt-4">
                {{ $stockItems->links('pagination.page_custom') }}
            </div>
        @endif
    </div>
@endsection
