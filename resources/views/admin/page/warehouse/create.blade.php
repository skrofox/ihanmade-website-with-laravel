@extends('layouts.admin.app')
@section('header', 'Tạo kho hàng mới')
@section('title', 'Tạo kho hàng mới')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tạo kho hàng mới</h2>
        
        <form action="{{ route('warehouse_store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Mã kho và Tên kho -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Mã kho <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="VD: WH001" required>
                    <p class="text-sm text-gray-500 mt-1">Mã định danh duy nhất cho kho hàng</p>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên kho <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="VD: Kho Hà Nội" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Địa chỉ -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Thông tin địa chỉ
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="street" class="block text-sm font-medium text-gray-700 mb-2">
                            Đường/Phố
                        </label>
                        <input type="text" name="street" id="street" value="{{ old('street') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="VD: 123 Nguyễn Huệ">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Thành phố/Quận
                        </label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="VD: Hà Nội">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                            Tỉnh/Bang
                        </label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="VD: Hà Nội">
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Mã bưu điện
                        </label>
                        <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="VD: 100000">
                    </div>

                    <div class="md:col-span-2">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            Quốc gia
                        </label>
                        <input type="text" name="country" id="country" value="{{ old('country', 'Việt Nam') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="VD: Việt Nam">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('warehouse_index') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                    Hủy
                </a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                    Tạo kho hàng
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
