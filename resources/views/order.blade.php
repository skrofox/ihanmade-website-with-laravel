{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.web.app')

@section('title', 'Đơn hàng của tôi - Ihandmade.com')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-medium">Đơn hàng của tôi</span>
            </nav>

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Đơn hàng của tôi</h1>
                    <p class="text-gray-600">Theo dõi và quản lý các đơn hàng của bạn</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <select
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="shipped">Đang giao</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
            </div>

            <div class="content">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Shipping Address</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->shipping_address }}</td>
                                <td>{{ $order->formatted_grand_total }}</td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    @if ($order->status == 'completed')
                                        <a href=""
                                            class="text-blue-600 hover:text-blue-800">Chi tiết</a>
                                    @else
                                    <a href="" class="text-red-600 hover:text-red-800">Hủy đơn</a>
                                            @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Không có đơn hàng nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (isset($orders) && method_exists($orders, 'links'))
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Order Detail Modal --}}
    <div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Chi tiết đơn hàng</h2>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function cancelOrder(orderId) {
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                // Add your cancel order logic here
                fetch(`/orders/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra, vui lòng thử lại!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    });
            }
        }

        function closeModal() {
            document.getElementById('orderDetailModal').classList.add('hidden');
        }

        // Filter orders by status
        document.querySelector('select').addEventListener('change', function() {
            const status = this.value;
            const url = new URL(window.location);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location = url;
        });
    </script>
@endpush

{{-- resources/views/orders/show.blade.php --}}
{{-- Separate file for order detail view --}}
{{-- 
@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' - Ihandmade.com')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('orders.index') }}" class="hover:text-blue-600 transition-colors">Đơn hàng</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Chi tiết đơn hàng</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Header -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                                Đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </h1>
                            <p class="text-gray-600">Đặt ngày {{ \Carbon\Carbon::parse($order->placed_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @switch($order->status)
                                @case('delivered')
                                    <span class="inline-flex px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                                        Đã giao hàng
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex px-4 py-2 rounded-lg text-sm font-medium bg-purple-100 text-purple-800">
                                        Đang xử lý
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex px-4 py-2 rounded-lg text-sm font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                            @endswitch
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Sản phẩm đã đặt</h3>
                        <div class="divide-y divide-gray-200">
                            @foreach ($order->orderItems as $item)
                                <div class="py-4 flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0">
                                        <img src="https://images.unsplash.com/photo-{{ rand(1521572163474, 1586790170083) }}-6864f9cf17ab?w=100" 
                                             alt="{{ $item->name }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800">{{ $item->name }}</h4>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p>SKU: {{ $item->sku }}</p>
                                            @if ($item->variant_id)
                                                <p>Phân loại: {{ $item->variant->name ?? 'N/A' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Số lượng: {{ $item->qty }}</p>
                                        <p class="font-medium text-gray-800">{{ number_format($item->unit_price, 0, ',', '.') }}đ</p>
                                        @if ($item->discount_total > 0)
                                            <p class="text-sm text-green-600">-{{ number_format($item->discount_total, 0, ',', '.') }}đ</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Địa chỉ giao hàng</h3>
                    @if ($order->shippingAddress)
                        <div class="text-gray-600">
                            <p class="font-medium text-gray-800">{{ $order->shippingAddress->full_name }}</p>
                            <p>{{ $order->shippingAddress->phone }}</p>
                            <p>{{ $order->shippingAddress->address }}</p>
                            <p>{{ $order->shippingAddress->ward }}, {{ $order->shippingAddress->district }}, {{ $order->shippingAddress->province }}</p>
                        </div>
                    @endif
                </div>

                <!-- Order Notes -->
                @if ($order->notes)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ghi chú đơn hàng</h3>
                        <p class="text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tóm tắt đơn hàng</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-medium">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        @if ($order->discount_total > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-medium text-green-600">-{{ number_format($order->discount_total, 0, ',', '.') }}đ</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-medium">{{ number_format($order->shipping_total, 0, ',', '.') }}đ</span>
                        </div>
                        @if ($order->tax_total > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Thuế:</span>
                                <span class="font-medium">{{ number_format($order->tax_total, 0, ',', '.') }}đ</span>
                            </div>
                        @endif
                        <hr>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Tổng cộng:</span>
                            <span class="text-red-600">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="mt-6 space-y-3">
                        @if ($order->status == 'delivered')
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors">
                                Mua lại
                            </button>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg font-medium transition-colors">
                                Đánh giá sản phẩm
                            </button>
                        @endif
                        @if (in_array($order->status, ['pending', 'confirmed']))
                            <button onclick="cancelOrder({{ $order->id }})"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                                Hủy đơn hàng
                            </button>
                        @endif
                        <a href="{{ route('orders.index') }}" 
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg font-medium transition-colors">
                            Quay lại danh sách
                        </a>
                    </div>

                    <!-- Payment Status -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-800 mb-2">Trạng thái thanh toán</h4>
                        @if ($order->paid_at)
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Đã thanh toán
                            </span>
                            <p class="text-xs text-gray-600 mt-1">{{ \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}</p>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Chưa thanh toán
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelOrder(orderId) {
    const reason = prompt('Vui lòng nhập lý do hủy đơn hàng:');
    if (reason && reason.trim() !== '') {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    }
}
</script>
@endpush
@endsection
--}}
