# Chức năng Quản lý Hình ảnh Sản phẩm

## Tổng quan
Chức năng này cho phép quản trị viên thêm, xóa và sắp xếp hình ảnh cho sản phẩm trong hệ thống iHandmade.

## Tính năng chính

### 1. Tạo sản phẩm với hình ảnh
- **Route**: `POST /admin/product/create`
- **Chức năng**: Upload nhiều hình ảnh khi tạo sản phẩm mới
- **Hỗ trợ định dạng**: JPG, JPEG, PNG, GIF, WEBP
- **Kích thước tối đa**: 2MB mỗi file
- **Vị trí lưu trữ**: `storage/app/public/products/`

### 2. Thêm hình ảnh cho sản phẩm hiện có
- **Route**: `POST /admin/product/{id}/add-images`
- **Chức năng**: Thêm hình ảnh mới cho sản phẩm đã tồn tại
- **Tự động sắp xếp**: Hình ảnh mới sẽ được gán vị trí tiếp theo

### 3. Xóa hình ảnh
- **Route**: `DELETE /admin/product/{productId}/image/{imageId}`
- **Chức năng**: Xóa hình ảnh cụ thể khỏi sản phẩm
- **Tự động dọn dẹp**: File hình ảnh sẽ được xóa khỏi storage

### 4. Cập nhật vị trí hình ảnh
- **Route**: `PUT /admin/product/{productId}/image-position`
- **Chức năng**: Thay đổi thứ tự hiển thị của hình ảnh
- **Giao diện**: Input số trực tiếp trên mỗi hình ảnh

## Cấu trúc Database

### Bảng `product_images`
```sql
- id: bigint (primary key)
- product_id: bigint (foreign key -> products.id)
- url: string (đường dẫn file)
- position: integer (vị trí hiển thị)
- alt: string (mô tả hình ảnh)
- timestamps
```

### Model `ProductImage`
- **Relationship**: `belongsTo(Product::class)`
- **Fillable fields**: `product_id`, `url`, `position`, `alt`

### Model `Product`
- **Relationship**: `hasMany(ProductImage::class)`
- **Method**: `images()` - trả về collection hình ảnh

## Giao diện người dùng

### Trang tạo sản phẩm (`create_product.blade.php`)
- Form upload nhiều file với preview
- Validation và hiển thị lỗi
- Hướng dẫn định dạng file

### Trang chi tiết sản phẩm (`detail_product.blade.php`)
- Hiển thị hình ảnh hiện tại dạng grid
- Form thêm hình ảnh mới
- Nút xóa và cập nhật vị trí cho mỗi hình
- Hover effect với controls

## JavaScript Features

### AJAX Operations
- **Thêm hình ảnh**: FormData với file upload
- **Xóa hình ảnh**: Confirmation dialog
- **Cập nhật vị trí**: Real-time validation
- **Notifications**: Toast messages cho feedback

### Image Preview
- **Upload preview**: Hiển thị trước khi submit
- **File info**: Tên file và kích thước
- **Responsive grid**: Layout tự động điều chỉnh

## Bảo mật và Validation

### Server-side Validation
```php
'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048'
```

### File Storage
- **Disk**: `public` (có thể truy cập từ web)
- **Path**: `products/{timestamp}_{index}.{extension}`
- **Cleanup**: Tự động xóa file khi xóa record

### CSRF Protection
- Tất cả AJAX requests đều có CSRF token
- Middleware `auth` và `role` cho admin routes

## Cài đặt và Cấu hình

### 1. Tạo symbolic link
```bash
php artisan storage:link
```

### 2. Kiểm tra permissions
```bash
chmod -R 775 storage/app/public
chmod -R 775 public/storage
```

### 3. Cấu hình filesystem
```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

## Sử dụng

### Thêm hình ảnh khi tạo sản phẩm
1. Điền thông tin sản phẩm
2. Chọn file hình ảnh (có thể chọn nhiều)
3. Xem preview
4. Submit form

### Quản lý hình ảnh sản phẩm hiện có
1. Vào trang chi tiết sản phẩm
2. Sử dụng form "Thêm hình ảnh mới"
3. Xóa hình ảnh không mong muốn
4. Sắp xếp lại thứ tự hiển thị

## Troubleshooting

### Hình ảnh không hiển thị
- Kiểm tra symbolic link: `php artisan storage:link`
- Kiểm tra permissions của thư mục storage
- Kiểm tra đường dẫn trong database

### Upload thất bại
- Kiểm tra kích thước file (max 2MB)
- Kiểm tra định dạng file
- Kiểm tra disk space

### Performance
- Hình ảnh được lưu trữ local (không CDN)
- Có thể optimize bằng image compression
- Cân nhắc sử dụng queue cho xử lý hình ảnh lớn

## Mở rộng trong tương lai

### Tính năng có thể thêm
- **Image compression**: Tự động nén hình ảnh
- **Multiple sizes**: Tạo thumbnail và responsive images
- **Watermark**: Thêm logo/watermark tự động
- **CDN integration**: Sử dụng CDN cho hình ảnh
- **Bulk operations**: Xử lý nhiều hình ảnh cùng lúc
- **Image cropping**: Cắt và resize hình ảnh
- **Alt text management**: Quản lý mô tả hình ảnh
