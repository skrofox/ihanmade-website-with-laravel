@extends('layouts.admin.app')
@section('header', 'Chi tiết Stock Item')
@section('title', 'Chi tiết Stock Item')
@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Chi tiết Stock Item</h1>
                    <p class="text-gray-600 mt-2">Thông tin chi tiết về stock item</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('stock_edit', $stockItem->id) }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Sửa
                    </a>
                    <a href="{{ route('stock_index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Thông tin cơ bản -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">ID</label>
                        <p class="text-sm text-gray-900">{{ $stockItem->id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ngày tạo</label>
                        <p class="text-sm text-gray-900">{{ $stockItem->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Ngày cập nhật</label>
                        <p class="text-sm text-gray-900">{{ $stockItem->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Thông tin kho hàng -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin kho hàng</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tên kho</label>
                        <p class="text-sm text-gray-900">{{ $stockItem->warehouse->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Mã kho</label>
                        <p class="text-sm text-gray-900">{{ $stockItem->warehouse->code }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Địa chỉ</label>
                        <p class="text-sm text-gray-900">{{ $stockItem->warehouse->address_text }}</p>
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin sản phẩm</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tên sản phẩm</label>
                            <p class="text-sm text-gray-900">{{ $stockItem->variant->product->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">SKU</label>
                            <p class="text-sm text-gray-900">{{ $stockItem->variant->sku }}</p>
                        </div>
                        
                        @if($stockItem->variant->barcode)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Barcode</label>
                            <p class="text-sm text-gray-900">{{ $stockItem->variant->barcode }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Trạng thái</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                       {{ $stockItem->variant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $stockItem->variant->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @if($stockItem->variant->option_value)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tùy chọn</label>
                            <p class="text-sm text-gray-900">{{ $stockItem->variant->option_value_text }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Mô tả</label>
                            <p class="text-sm text-gray-900">{{ $stockItem->variant->product->description ?? 'Không có mô tả' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin stock -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin tồn kho</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-blue-600 mb-2">Tồn kho</label>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($stockItem->on_hand) }}</p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-yellow-600 mb-2">Đặt trước</label>
                            <p class="text-2xl font-bold text-yellow-900">{{ number_format($stockItem->reserved) }}</p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-green-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-green-600 mb-2">Có sẵn</label>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($stockItem->available_stock) }}</p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-purple-600 mb-2">Mức stock</label>
                            @php
                                $stockLevelClass = [
                                    'low' => 'text-red-600',
                                    'normal' => 'text-green-600',
                                    'high' => 'text-yellow-600'
                                ];
                                $class = $stockLevelClass[$stockItem->stock_level] ?? 'text-gray-600';
                            @endphp
                            <p class="text-lg font-bold {{ $class }}">{{ $stockItem->stock_level_text }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Mức stock cảnh báo -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Mức stock tối thiểu</label>
                        <p class="text-sm text-gray-900">{{ number_format($stockItem->min_stock_level) }}</p>
                        @if($stockItem->on_hand <= $stockItem->min_stock_level)
                            <p class="text-sm text-red-600 mt-1">⚠️ Stock đang ở mức thấp!</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Mức stock tối đa</label>
                        <p class="text-sm text-gray-900">{{ number_format($stockItem->max_stock_level) }}</p>
                        @if($stockItem->on_hand >= $stockItem->max_stock_level)
                            <p class="text-sm text-yellow-600 mt-1">⚠️ Stock đang ở mức cao!</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Các thao tác -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thao tác</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button onclick="showStockModal({{ $stockItem->id }})" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Điều chỉnh Stock
                    </button>
                    
                    <button onclick="showReserveModal({{ $stockItem->id }})" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Đặt trước Stock
                    </button>
                    
                    <button onclick="showReleaseModal({{ $stockItem->id }})" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Giải phóng Stock
                    </button>
                </div>
            </div>
        </div>
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

    <!-- Modal đặt trước stock -->
    <div id="reserveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Đặt trước Stock</h3>
                <form id="reserveStockForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="reserveQuantity" class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                        <input type="number" id="reserveQuantity" name="quantity" min="1" max="{{ $stockItem->available_stock }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                        <p class="text-sm text-gray-500">Tối đa: {{ number_format($stockItem->available_stock) }}</p>
                    </div>
                    
                    <div>
                        <label for="reserveReason" class="block text-sm font-medium text-gray-700 mb-1">Lý do (tùy chọn)</label>
                        <textarea id="reserveReason" name="reason" rows="2"
                                  class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideReserveModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Đặt trước
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal giải phóng stock -->
    <div id="releaseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Giải phóng Stock đặt trước</h3>
                <form id="releaseStockForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="releaseQuantity" class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                        <input type="number" id="releaseQuantity" name="quantity" min="1" max="{{ $stockItem->reserved }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">
                        <p class="text-sm text-gray-500">Tối đa: {{ number_format($stockItem->reserved) }}</p>
                    </div>
                    
                    <div>
                        <label for="releaseReason" class="block text-sm font-medium text-gray-700 mb-1">Lý do (tùy chọn)</label>
                        <textarea id="releaseReason" name="reason" rows="2"
                                  class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideReleaseModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                            Giải phóng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStockId = {{ $stockItem->id }};

        function showStockModal(stockId) {
            currentStockId = stockId;
            document.getElementById('stockModal').classList.remove('hidden');
        }

        function hideStockModal() {
            document.getElementById('stockModal').classList.add('hidden');
            document.getElementById('adjustStockForm').reset();
        }

        function showReserveModal(stockId) {
            currentStockId = stockId;
            document.getElementById('reserveModal').classList.remove('hidden');
        }

        function hideReserveModal() {
            document.getElementById('reserveModal').classList.add('hidden');
            document.getElementById('reserveStockForm').reset();
        }

        function showReleaseModal(stockId) {
            currentStockId = stockId;
            document.getElementById('releaseModal').classList.remove('hidden');
        }

        function hideReleaseModal() {
            document.getElementById('releaseModal').classList.add('hidden');
            document.getElementById('releaseStockForm').reset();
        }

        // Xử lý form điều chỉnh stock
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

        // Xử lý form đặt trước stock
        document.getElementById('reserveStockForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = `/admin/stock-items/${currentStockId}/reserve-stock`;
            
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
            
            hideReserveModal();
        });

        // Xử lý form giải phóng stock
        document.getElementById('releaseStockForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = `/admin/stock-items/${currentStockId}/release-reserved`;
            
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
            
            hideReleaseModal();
        });
    </script>
@endsection
