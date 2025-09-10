@extends('layouts.admin.app')
@section('header', 'Chi tiết kho hàng')
@section('title', 'Chi tiết kho hàng')
@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Chi tiết kho hàng</h2>
            <div class="flex space-x-3">
                <a href="{{ route('warehouse_edit', $warehouse->id) }}" 
                   class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">
                    Sửa kho hàng
                </a>
                <a href="{{ route('warehouse_index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Thông tin cơ bản -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mã kho:</label>
                            <p class="text-sm text-gray-900 font-semibold">{{ $warehouse->code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tên kho:</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ngày tạo:</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cập nhật lần cuối:</label>
                            <p class="text-sm text-gray-900">{{ $warehouse->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Địa chỉ</h3>
                    <div class="space-y-3">
                        @if($warehouse->address && is_array($warehouse->address))
                            @if(!empty($warehouse->address['street']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Đường/Phố:</label>
                                <p class="text-sm text-gray-900">{{ $warehouse->address['street'] }}</p>
                            </div>
                            @endif
                            @if(!empty($warehouse->address['city']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Thành phố/Quận:</label>
                                <p class="text-sm text-gray-900">{{ $warehouse->address['city'] }}</p>
                            </div>
                            @endif
                            @if(!empty($warehouse->address['state']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tỉnh/Bang:</label>
                                <p class="text-sm text-gray-900">{{ $warehouse->address['state'] }}</p>
                            </div>
                            @endif
                            @if(!empty($warehouse->address['zip_code']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mã bưu điện:</label>
                                <p class="text-sm text-gray-900">{{ $warehouse->address['zip_code'] }}</p>
                            </div>
                            @endif
                            @if(!empty($warehouse->address['country']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quốc gia:</label>
                                <p class="text-sm text-gray-900">{{ $warehouse->address['country'] }}</p>
                            </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">Không có thông tin địa chỉ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm trong kho -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Sản phẩm trong kho ({{ $warehouse->stockItems->count() }})</h3>
            
            @if($warehouse->stockItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Biến thể
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số lượng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày cập nhật
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($warehouse->stockItems as $stockItem)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($stockItem->productVariant && $stockItem->productVariant->product)
                                        {{ $stockItem->productVariant->product->name }}
                                    @else
                                        <span class="text-red-500">Sản phẩm đã bị xóa</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($stockItem->productVariant)
                                        {{ $stockItem->productVariant->sku }}
                                    @else
                                        <span class="text-red-500">Biến thể đã bị xóa</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-semibold">{{ $stockItem->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $stockItem->updated_at->format('d/m/Y H:i') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-500">Chưa có sản phẩm nào trong kho này</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
