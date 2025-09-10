@extends('layouts.admin.app')
@section('header', 'Quản lý Stock Items')
@section('title', 'Stock Items Dashboard')
@section('content')
    <div class="space-y-4">
        <!-- Header với form tìm kiếm và lọc -->
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Quản lý Stock Items</h1>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('stock_create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Thêm Stock Item
                </a>
                <a href="{{ route('stock_trash') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Thùng rác
                </a>
            </div>
        </div>

        <!-- Form tìm kiếm và lọc -->
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <form action="{{ route('stock_index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                        <input type="text" name="keyword" id="keyword" 
                               placeholder="SKU, barcode, tên sản phẩm..." 
                               value="{{ request('keyword') }}"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label for="warehouse" class="block text-sm font-medium text-gray-700 mb-1">Kho hàng</label>
                        <select name="warehouse" id="warehouse" 
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                            <option value="">Tất cả kho</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ request('warehouse') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="stock_level" class="block text-sm font-medium text-gray-700 mb-1">Mức stock</label>
                        <select name="stock_level" id="stock_level" 
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                            <option value="">Tất cả</option>
                            @foreach($stockLevels as $key => $label)
                                <option value="{{ $key }}" {{ request('stock_level') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bảng hiển thị stock items -->
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
                                Đặt trước
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Có sẵn
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mức stock
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
                                    {{ number_format($stockItem->reserved) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($stockItem->available_stock) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $stockLevelClass = [
                                            'low' => 'bg-red-100 text-red-800',
                                            'normal' => 'bg-green-100 text-green-800',
                                            'high' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                        $class = $stockLevelClass[$stockItem->stock_level] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $class }}">
                                        {{ $stockItem->stock_level_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('stock_show', $stockItem->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">Xem</a>
                                        <a href="{{ route('stock_edit', $stockItem->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                        <button onclick="showStockModal({{ $stockItem->id }})" 
                                                class="text-green-600 hover:text-green-900">Điều chỉnh</button>
                                        <form action="{{ route('stock_destroy', $stockItem->id) }}" method="POST" 
                                              class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Không có stock items nào
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

    <!-- Modal điều chỉnh stock -->
    <div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Điều chỉnh Stock</h3>
                <form id="adjustStockForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="adjustType" class="block text-sm font-medium text-gray-700 mb-1">Loại điều chỉnh</label>
                        <select id="adjustType" name="type" required 
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                            <option value="add">Thêm vào</option>
                            <option value="subtract">Trừ đi</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="adjustQuantity" class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                        <input type="number" id="adjustQuantity" name="quantity" min="1" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <label for="adjustReason" class="block text-sm font-medium text-gray-700 mb-1">Lý do (tùy chọn)</label>
                        <textarea id="adjustReason" name="reason" rows="2"
                                  class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideStockModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Điều chỉnh
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStockId = null;

        function showStockModal(stockId) {
            currentStockId = stockId;
            document.getElementById('stockModal').classList.remove('hidden');
        }

        function hideStockModal() {
            document.getElementById('stockModal').classList.add('hidden');
            currentStockId = null;
            document.getElementById('adjustStockForm').reset();
        }

        document.getElementById('adjustStockForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = `/admin/stock-items/${currentStockId}/adjust-stock`;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra');
            });
            
            hideStockModal();
        });
    </script>
@endsection
