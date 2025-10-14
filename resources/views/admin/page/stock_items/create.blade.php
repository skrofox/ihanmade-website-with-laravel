@extends('layouts.admin.app')
@section('header', 'Thêm Stock Item')
@section('title', 'Thêm Stock Item')
@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Thêm Stock Item Mới</h1>
            <p class="text-gray-600 mt-2">Tạo mới stock item để quản lý tồn kho</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form action="{{ route('stock_store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Kho hàng -->
                <div>
                    <label for="ware_house_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kho hàng <span class="text-red-500">*</span>
                    </label>
                    <select name="ware_house_id" id="ware_house_id" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none @error('ware_house_id') border-red-500 @enderror">
                        <option value="">Chọn kho hàng</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('ware_house_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }} ({{ $warehouse->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('ware_house_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Biến thể sản phẩm -->
                <div>
                    <label for="variant_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Biến thể sản phẩm <span class="text-red-500">*</span>
                    </label>
                    <select name="variant_id" id="variant_id" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none @error('variant_id') border-red-500 @enderror">
                        <option value="">Chọn biến thể sản phẩm</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant->id }}" {{ old('variant_id') == $variant->id ? 'selected' : '' }}>
                                {{ $variant->product->name ?? 'N/A' }} - SKU: {{ $variant->sku }}
                                @if($variant->barcode)
                                    (Barcode: {{ $variant->barcode }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('variant_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Số lượng tồn kho -->
                <div>
                    <label for="on_hand" class="block text-sm font-medium text-gray-700 mb-2">
                        Số lượng tồn kho <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="on_hand" id="on_hand" min="0" required
                           value="{{ old('on_hand', 0) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none @error('on_hand') border-red-500 @enderror"
                           placeholder="Nhập số lượng tồn kho">
                    @error('on_hand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Số lượng đặt trước -->
                <div class="hidden">
                    <label for="reserved" class="block text-sm font-medium text-gray-700 mb-2">
                        Số lượng đặt trước
                    </label>
                    <input type="number" name="reserved" id="reserved" min="0"
                           value="{{ old('reserved', 0) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none @error('reserved') border-red-500 @enderror"
                           placeholder="Nhập số lượng đặt trước">
                    <p class="text-gray-500 text-sm mt-1">Số lượng đã được đặt trước (không được lớn hơn số lượng tồn kho)</p>
                    @error('reserved')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mức stock tối thiểu -->
                <div>
                    <label for="min_stock_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Mức stock tối thiểu
                    </label>
                    <input type="number" name="min_stock_level" id="min_stock_level" min="0"
                           value="{{ old('min_stock_level', 0) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none @error('min_stock_level') border-red-500 @enderror"
                           placeholder="Nhập mức stock tối thiểu">
                    <p class="text-gray-500 text-sm mt-1">Khi stock xuống dưới mức này sẽ được cảnh báo</p>
                    @error('min_stock_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mức stock tối đa -->
                <div>
                    <label for="max_stock_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Mức stock tối đa
                    </label>
                    <input type="number" name="max_stock_level" id="max_stock_level" min="0"
                           value="{{ old('max_stock_level', 0) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none @error('max_stock_level') border-red-500 @enderror"
                           placeholder="Nhập mức stock tối đa">
                    <p class="text-gray-500 text-sm mt-1">Khi stock vượt quá mức này sẽ được cảnh báo</p>
                    @error('max_stock_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thông báo lỗi chung -->
                @if($errors->has('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-600 text-sm">{{ $errors->first('error') }}</p>
                    </div>
                @endif

                <!-- Nút điều khiển -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('stock_index') }}" 
                       class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Tạo Stock Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validation real-time
        document.getElementById('reserved').addEventListener('input', function() {
            const onHand = parseInt(document.getElementById('on_hand').value) || 0;
            const reserved = parseInt(this.value) || 0;
            
            if (reserved > onHand) {
                this.setCustomValidity('Số lượng đặt trước không được lớn hơn số lượng tồn kho');
            } else {
                this.setCustomValidity('');
            }
        });

        document.getElementById('on_hand').addEventListener('input', function() {
            const reserved = document.getElementById('reserved');
            if (reserved.value) {
                reserved.dispatchEvent(new Event('input'));
            }
        });
    </script>
@endsection
