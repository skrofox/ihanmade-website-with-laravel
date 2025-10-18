@extends('layouts.admin.app')
@section('header', 'Chi tiết đơn hàng')
@section('title', 'Order Detail')
@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

    <div class="space-y-6">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Đơn hàng #{{ $order->id }}</h1>
                    <p class="text-gray-600">Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @switch($order->status)
                        @case('placed')
                            bg-yellow-100 text-yellow-800
                            @break
                        @case('paid')
                            bg-blue-100 text-blue-800
                            @break
                        @case('fulfilling')
                            bg-purple-100 text-purple-800
                            @break
                        @case('shipped')
                            bg-indigo-100 text-indigo-800
                            @break
                        @case('completed')
                            bg-green-100 text-green-800
                            @break
                        @case('cancelled')
                            bg-red-100 text-red-800
                            @break
                        @default
                            bg-gray-100 text-gray-800
                    @endswitch">
                        {{ $order->status_text }}
                    </span>
                    @if ($order->cancel_reason)
                        <p class="text-sm text-red-600 mt-1">Lý do hủy: {{ $order->cancel_reason }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Thông tin khách hàng</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="font-medium">Email:</span> {{ $order->email }}</p>
                        @if ($order->user)
                            <p><span class="font-medium">Tên:</span> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><span class="font-medium">ID User:</span> {{ $order->user->id }}</p>
                        @else
                            <p><span class="font-medium">Loại:</span> Khách hàng</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Thông tin giao hàng</h3>
                    @if ($order->shippingAddress)
                        <div class="text-sm space-y-1">
                            <p><span class="font-medium">Người nhận:</span> {{ $order->shippingAddress->full_name }}</p>
                            <p><span class="font-medium">SĐT:</span> {{ $order->shippingAddress->phone }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->shippingAddress->line1 }}</p>
                        </div>
                    @else
                        <p class="text-gray-500">Không có thông tin địa chỉ</p>
                    @endif
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Thông tin đơn hàng</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="font-medium">Tổng tiền:</span> <span
                                class="text-green-600 font-semibold">{{ $order->formatted_grand_total }}</span></p>
                        <p><span class="font-medium">Số sản phẩm:</span> {{ $order->items->count() }} items</p>
                        @if ($order->notes)
                            <p><span class="font-medium">Ghi chú:</span> {{ $order->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-gray-800">Chi tiết sản phẩm</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản
                                phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn
                                giá</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số
                                lượng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành
                                tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($item->product && $item->product->main_image)
                                            <img src="{{ Storage::url($item->product->main_image->url) }}"
                                                alt="{{ $item->name }}" class="w-12 h-12 object-cover rounded-lg mr-4">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-800">{{ $item->name }}</div>
                                            @if ($item->variant && $item->variant->option_value_text !== 'Không có')
                                                <div class="text-sm text-gray-600">{{ $item->variant->option_value_text }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item->sku }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item->formatted_unit_price }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $item->qty }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                    {{ $item->formatted_subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tổng kết đơn hàng</h2>

            <div class="max-w-md ml-auto space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="font-medium">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                </div>

                @if ($order->shipping_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span class="font-medium">{{ number_format($order->shipping_total, 0, ',', '.') }}đ</span>
                    </div>
                @endif

                @if ($order->discount_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Giảm giá:</span>
                        <span
                            class="font-medium text-green-600">-{{ number_format($order->discount_total, 0, ',', '.') }}đ</span>
                    </div>
                @endif

                @if ($order->tax_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Thuế:</span>
                        <span class="font-medium">{{ number_format($order->tax_total, 0, ',', '.') }}đ</span>
                    </div>
                @endif

                <hr class="my-2">

                <div class="flex justify-between text-lg font-bold">
                    <span>Tổng cộng:</span>
                    <span class="text-green-600">{{ $order->formatted_grand_total }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('order_index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Quay lại danh sách
            </a>

            @if ($order->status !== 'completed' && $order->status !== 'cancelled')
                <button onclick="updateOrderStatus({{ $order->id }}, '{{ $order->status }}')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Cập nhật trạng thái
                </button>
            @endif
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Cập nhật trạng thái đơn hàng</h3>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái mới</label>
                        <select name="status" id="statusSelect"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="placed">Đã đặt hàng</option>
                            <option value="paid">Đã thanh toán</option>
                            <option value="fulfilling">Đang chuẩn bị</option>
                            <option value="shipped">Đã giao hàng</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <div class="mb-4" id="cancelReasonDiv" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lý do hủy</label>
                        <textarea name="cancel_reason" rows="3"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nhập lý do hủy đơn hàng..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Cập
                            nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateOrderStatus(orderId, currentStatus) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const statusSelect = document.getElementById('statusSelect');
            const cancelReasonDiv = document.getElementById('cancelReasonDiv');

            form.action = `/admin/order/update-status/${orderId}`;
            statusSelect.value = currentStatus;

            // Show/hide cancel reason based on status
            statusSelect.addEventListener('change', function() {
                if (this.value === 'cancelled') {
                    cancelReasonDiv.style.display = 'block';
                } else {
                    cancelReasonDiv.style.display = 'none';
                }
            });

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
