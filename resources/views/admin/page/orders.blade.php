@extends('layouts.admin.app')
@section('header', 'Page Orders')
@section('title', 'Orders Dashboard')
@section('content')
    <div class="space-y-4">
        <h1 style="margin:0; font-size:1.1rem;">
            <form action="{{ route('order_search') }}" method="get" class="flex gap-2 items-center">
                <input type="text" name="keyword" id="keyword" placeholder="ID, email, status..." value="{{ request('keyword') }}"
                    class="rounded-sm border border-collapse px-3 py-2">
                <select name="status" class="rounded-sm border border-collapse px-3 py-2">
                    <option value="">All Status</option>
                    <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Đã đặt hàng</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="fulfilling" {{ request('status') == 'fulfilling' ? 'selected' : '' }}>Đang chuẩn bị</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã giao hàng</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                <button type="submit" class="bg-slate-300 hover:bg-slate-500 px-4 py-2 rounded border border-collapse">
                    Search
                </button>
            </form>
        </h1>
        
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
            <table class="table min-w-[1000px] w-full text-left text-lg">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 w-16">#</th>
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Order Date</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50" id="order-row-{{ $order->id }}">
                            <td scope="row" class="text-center px-4 py-3 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm">#{{ $order->id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($order->user)
                                    <div class="font-medium">{{ $order->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $order->user->id }}</div>
                                @else
                                    <span class="text-gray-500">Guest</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm">{{ $order->email }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-green-600">{{ $order->formatted_grand_total }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm">{{ $order->items->count() }} items</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('order_detail', $order->id) }}" 
                                       class="inline-flex items-center gap-1 rounded-lg border border-blue-300 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100 active:scale-[.98]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                    <button onclick="updateOrderStatus({{ $order->id }}, '{{ $order->status }}')"
                                            class="inline-flex items-center gap-1 rounded-lg border border-green-300 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100 active:scale-[.98]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Update
                                    </button>
                                    @endif
                                    <form action="{{ route('order_destroy', $order->id) }}" method="post" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng #{{ $order->id }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 active:scale-[.98]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                Không có đơn hàng nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="mt-3">
            {{ $orders->links('pagination.page_custom') }}
        </div>
        @endif
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
                        <select name="status" id="statusSelect" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="placed">Đã đặt hàng</option>
                            <option value="paid">Đã thanh toán</option>
                            <option value="fulfilling">Đang chuẩn bị</option>
                            <option value="shipped">Đang giao hàng</option>
                            {{-- <option value="completed">Hoàn thành</option> --}}
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <div class="mb-4" id="cancelReasonDiv" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lý do hủy</label>
                        <textarea name="cancel_reason" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập lý do hủy đơn hàng..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Cập nhật</button>
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
