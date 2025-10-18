@extends('layouts.admin.app')
@section('header', 'Sửa biến thể sản phẩm')
@section('title', 'Sửa biến thể sản phẩm')
@section('content')
    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Sửa biến thể sản phẩm</h2>

            <form action="{{ route('variant_update', $variant->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Chọn sản phẩm -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Sản phẩm <span class="text-red-500">*</span>
                        </label>
                        <select name="product_id" id="product_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach ($products as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('product_id', $variant->product_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                            SKU <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $variant->sku) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="VD: PROD-001-RED-L" required>
                        <p class="text-sm text-gray-500 mt-1">Mã định danh duy nhất cho biến thể</p>
                        @error('sku')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Barcode -->
                <div>
                    <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                        Mã vạch
                    </label>
                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $variant->barcode) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nhập mã vạch nếu có">
                    @error('barcode')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Option Values -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tùy chọn biến thể
                    </label>
                    <div id="option-container" class="space-y-3">
                        @if ($variant->option_value && is_array($variant->option_value))
                            @foreach ($variant->option_value as $key => $value)
                                <div class="option-row flex gap-3">
                                    <input type="text" name="option_keys[]" value="{{ $key }}"
                                        placeholder="Tên thuộc tính (VD: Màu sắc)"
                                        class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <input type="text" name="option_values[]" value="{{ $value }}"
                                        placeholder="Giá trị (VD: Đỏ)"
                                        class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button"
                                        class="remove-option bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600">Xóa</button>
                                </div>
                            @endforeach
                        @else
                            <div class="option-row flex gap-3">
                                <input type="text" name="option_keys[]" placeholder="Tên thuộc tính (VD: Màu sắc)"
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <input type="text" name="option_values[]" placeholder="Giá trị (VD: Đỏ)"
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button"
                                    class="remove-option bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600"
                                    style="display: none;">Xóa</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-option"
                        class="mt-3 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        + Thêm tùy chọn
                    </button>
                    <p class="text-sm text-gray-500 mt-1">VD: Màu sắc: Đỏ, Kích thước: L</p>
                    @error('option_value')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Giá bán (đ)
                    </label>
                    @if ($variant->prices->first())
                        <input type="number"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            name="price"
                            value="{{ old('price', number_format((float) $variant->prices->first()->list_priced, 0, ',', '.')) }}"
                            required>
                    @else
                        <input type="number"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            name="price"
                            value="{{ old('price') }}"
                            required>
                    @endif
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Trạng thái
                    </label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ old('status', $variant->status) == 'active' ? 'selected' : '' }}>Hoạt
                            động</option>
                        <option value="inactive" {{ old('status', $variant->status) == 'inactive' ? 'selected' : '' }}>
                            Không hoạt động</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('variant_index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                        Hủy
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                        Cập nhật biến thể
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addOptionBtn = document.getElementById('add-option');
            const optionContainer = document.getElementById('option-container');

            addOptionBtn.addEventListener('click', function() {
                const optionRow = document.createElement('div');
                optionRow.className = 'option-row flex gap-3';
                optionRow.innerHTML = `
            <input type="text" name="option_keys[]" placeholder="Tên thuộc tính" 
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <input type="text" name="option_values[]" placeholder="Giá trị" 
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="button" class="remove-option bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600">Xóa</button>
        `;

                optionContainer.appendChild(optionRow);
                updateRemoveButtons();
            });

            optionContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-option')) {
                    e.target.parentElement.remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const rows = optionContainer.querySelectorAll('.option-row');
                rows.forEach((row, index) => {
                    const removeBtn = row.querySelector('.remove-option');
                    if (rows.length === 1) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'block';
                    }
                });
            }

            updateRemoveButtons();
        });
    </script>
@endsection
