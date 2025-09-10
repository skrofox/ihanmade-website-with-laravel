@extends('layouts.admin.app')
@section('title', 'Detail Product')
@section('header', 'Detail Product')
@section('content')
    <div class="flex">
        <div class="w-1/2 bg-slate-200 p-6">
            @if (session()->has('message'))
                <div class="text-green-600 mt-2">{{ session('message') }}</div>
            @endif
            @error('name')
                <div class="text-red-600 mt-1">{{ $message }}</div>
            @enderror

            <input type="hidden" id="product-id" value="{{ $product->id }}">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên sản phẩm</label>
                <input type="text" value="{{ $product->name }}" placeholder="name product..." class="w-full border border-gray-300 rounded-md px-3 py-2" id="name">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                @foreach ($categories as $category)
                    <label class="inline-flex items-center mr-4 mb-2">
                        <input type="checkbox" name="categories[]" class="category-checkbox rounded border-gray-300" value="{{ $category->id }}"
                            {{ $product->categories->contains($category->id) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                <textarea name="description" id="description" cols="30" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2">{{ $product->description }}</textarea>
            </div>
            
            <div class="mb-4">
                <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Thương hiệu</label>
                <input type="text" value="{{ $product->brand }}" class="w-full border border-gray-300 rounded-md px-3 py-2" id="brand">
            </div>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>
        
        <div class="w-1/2 bg-slate-100 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Hình ảnh sản phẩm</h3>
                
                <!-- Form thêm hình ảnh mới -->
                <form id="addImageForm" class="mb-6 p-4 bg-white rounded-lg border">
                    <label for="newImages" class="block text-sm font-medium text-gray-700 mb-2">Thêm hình ảnh mới</label>
                    <input type="file" id="newImages" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 
                        file:mr-4 file:py-2 file:px-4 
                        file:rounded-full file:border-0 
                        file:text-sm file:font-semibold 
                        file:bg-blue-50 file:text-blue-700 
                        hover:file:bg-blue-100 mb-3">
                    
                    <div id="imagePreview" class="flex flex-wrap gap-2 mb-3"></div>
                    
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 text-sm">
                        Thêm hình ảnh
                    </button>
                </form>
                
                <!-- Hiển thị hình ảnh hiện tại -->
                <div id="currentImages" class="grid grid-cols-2 gap-4">
                    @foreach($product->images->sortBy('position') as $image)
                        <div class="image-item relative group" data-image-id="{{ $image->id }}" data-position="{{ $image->position }}">
                            <img src="{{ asset('storage/' . $image->url) }}" 
                                 alt="{{ $image->alt }}" 
                                 class="w-full h-32 object-cover rounded-lg border">
                            
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                <button class="delete-image-btn bg-red-500 text-white p-2 rounded-full hover:bg-red-600 mr-2" 
                                        data-image-id="{{ $image->id }}" 
                                        title="Xóa hình ảnh">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                
                                <div class="flex flex-col items-center">
                                    <span class="text-white text-xs mb-1">Vị trí: {{ $image->position }}</span>
                                    <input type="number" 
                                           class="position-input w-16 text-center text-xs bg-white text-gray-800 rounded px-1 py-1" 
                                           value="{{ $image->position }}" 
                                           min="0">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($product->images->count() == 0)
                    <div class="text-center text-gray-500 py-8">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p>Chưa có hình ảnh nào</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div id="notification"
            style="display:none; 
            position:fixed; 
            top:20px; 
            right:20px; 
            background:#16a34a; 
            color:white; 
            padding:10px 15px; 
            border-radius:6px; 
            box-shadow:0 2px 6px rgba(0,0,0,0.2); 
            z-index:9999;">
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Xử lý cập nhật thông tin sản phẩm
            $('.category-checkbox, #name, #description, #brand, #status').on('blur change',
                function() {
                    var input = $(this);
                    var id = $('#product-id').val();
                    var name = $('#name').val();
                    var description = $('#description').val();
                    var brand = $('#brand').val();
                    var status = $('#status').val();
                    
                    var categories = [];
                    $('.category-checkbox:checked').each(function() {
                        categories.push($(this).val())
                    })
                    
                    $.ajax({
                        url: '/admin/product/' + id + '/update-fields',
                        method: 'POST',
                        data: {
                            name: name,
                            description: description,
                            brand: brand,
                            status: status,
                            categories: categories,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                showNotification("Sửa thành công", 'success');
                            } else {
                                input.css('background', '#fee2e2');
                                showNotification(res.message, 'error');
                            }
                        },
                        error: function() {
                            input.css('background', '#fee2e2');
                            showNotification('Có lỗi xảy ra!', 'error');
                        }
                    });
                });

            // Xử lý preview hình ảnh mới
            $('#newImages').on('change', function() {
                const files = this.files;
                const preview = $('#imagePreview');
                preview.empty();
                
                Array.from(files).forEach(file => {
                    if (file.type.startsWith("image/")) {
                        const wrapper = $('<div class="w-20 h-20 border rounded-lg overflow-hidden shadow"></div>');
                        const img = $('<img class="w-full h-full object-cover">');
                        img.attr('src', URL.createObjectURL(file));
                        wrapper.append(img);
                        preview.append(wrapper);
                        
                        img.on('load', () => URL.revokeObjectURL(img.attr('src')));
                    }
                });
            });

            // Xử lý thêm hình ảnh mới
            $('#addImageForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData();
                const files = $('#newImages')[0].files;
                
                if (files.length === 0) {
                    showNotification('Vui lòng chọn ít nhất một hình ảnh', 'error');
                    return;
                }
                
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }
                formData.append('_token', '{{ csrf_token() }}');
                
                $.ajax({
                    url: '/admin/product/{{ $product->id }}/add-images',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            showNotification(res.message, 'success');
                            $('#newImages').val('');
                            $('#imagePreview').empty();
                            // Reload trang để hiển thị hình ảnh mới
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification(res.message, 'error');
                        }
                    },
                    error: function() {
                        showNotification('Có lỗi xảy ra khi thêm hình ảnh!', 'error');
                    }
                });
            });

            // Xử lý xóa hình ảnh
            $(document).on('click', '.delete-image-btn', function() {
                if (!confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
                    return;
                }
                
                const imageId = $(this).data('image-id');
                const imageItem = $(this).closest('.image-item');
                
                $.ajax({
                    url: '/admin/product/{{ $product->id }}/image/' + imageId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            showNotification(res.message, 'success');
                            imageItem.fadeOut(300, function() {
                                $(this).remove();
                                // Kiểm tra nếu không còn hình ảnh nào
                                if ($('.image-item').length === 0) {
                                    location.reload();
                                }
                            });
                        } else {
                            showNotification(res.message, 'error');
                        }
                    },
                    error: function() {
                        showNotification('Có lỗi xảy ra khi xóa hình ảnh!', 'error');
                    }
                });
            });

            // Xử lý cập nhật vị trí hình ảnh
            $(document).on('blur', '.position-input', function() {
                const newPosition = $(this).val();
                const imageId = $(this).closest('.image-item').data('image-id');
                
                $.ajax({
                    url: '/admin/product/{{ $product->id }}/image-position',
                    method: 'PUT',
                    data: {
                        image_id: imageId,
                        position: newPosition,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            showNotification('Cập nhật vị trí thành công', 'success');
                        } else {
                            showNotification('Có lỗi xảy ra khi cập nhật vị trí!', 'error');
                        }
                    },
                    error: function() {
                        showNotification('Có lỗi xảy ra khi cập nhật vị trí!', 'error');
                    }
                });
            });
        });

        function showNotification(message, type = 'success') {
            var notif = $('#notification');
            notif.stop(true, true);

            if (type === 'success') {
                notif.css('background', '#16a34a');
            } else if (type === 'error') {
                notif.css('background', '#dc2626');
            } else {
                notif.css('background', '#6b7280');
            }

            notif.text(message).fadeIn(200).delay(2000).fadeOut(500);
        }
    </script>
@endsection
