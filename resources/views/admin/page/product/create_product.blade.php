@extends('layouts.admin.app')
@section('title', 'Create Product')
@section('header', 'Create Product')
@section('content')
    @if (session('success'))
        <div class="text-green-600 mt-2">{{ session('success') }}</div>
    @endif
    @error('name')
        <div class="text-red-600 mt-1">{{ $message }}</div>
    @enderror
    @error('images')
        <div class="text-red-600 mt-1">{{ $message }}</div>
    @enderror
    @error('images.*')
        <div class="text-red-600 mt-1">{{ $message }}</div>
    @enderror
    
    <form action="{{ route('product_store') }}" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="w-1/2 bg-slate-200 p-6">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" placeholder="name product..." class="w-full border border-gray-300 rounded-md px-3 py-2" id="name" name="name" required>
                </div>
                
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                    <select name="categories[]" id="category_id" class="w-full min-h-[200px] border border-gray-300 rounded-md px-3 py-2" multiple required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2"
                        placeholder="Mô tả sản phẩm..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Thương hiệu</label>
                    <input type="text" name="brand" class="w-full border border-gray-300 rounded-md px-3 py-2" id="brand" placeholder="brand...">
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>
            
            <div class="w-1/2 bg-slate-300 p-6">
                <div class="mb-6">
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh sản phẩm</label>
                    <input type="file" id="fileInput" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 
                        file:mr-4 file:py-2 file:px-4 
                        file:rounded-full file:border-0 
                        file:text-sm file:font-semibold 
                        file:bg-blue-50 file:text-blue-700 
                        hover:file:bg-blue-100 mb-6">

                    <div id="preview" class="flex flex-wrap gap-4 mb-6"></div>
                    
                    <div class="text-sm text-gray-500">
                        <p>• Hỗ trợ: JPG, JPEG, PNG, GIF, WEBP</p>
                        <p>• Kích thước tối đa: 2MB mỗi file</p>
                        <p>• Có thể chọn nhiều hình ảnh cùng lúc</p>
                    </div>
                </div>
                
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Tạo sản phẩm
                </button>
            </div>
        </div>
    </form>
    
    <script>
        const input = document.getElementById("fileInput");
        const preview = document.getElementById("preview");

        input.addEventListener("change", () => {
            preview.innerHTML = ""; // clear cũ
            const files = input.files;

            Array.from(files).forEach(file => {
                if (file.type.startsWith("image/")) {
                    const wrapper = document.createElement("div");
                    wrapper.className = "w-32 h-32 border rounded-lg overflow-hidden shadow relative";

                    const img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.className = "w-full h-full object-cover";

                    const fileName = document.createElement("div");
                    fileName.className = "absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white text-xs p-1 truncate";
                    fileName.textContent = file.name;

                    wrapper.appendChild(img);
                    wrapper.appendChild(fileName);
                    preview.appendChild(wrapper);

                    img.onload = () => URL.revokeObjectURL(img.src);
                }
            });
        });
    </script>
@endsection