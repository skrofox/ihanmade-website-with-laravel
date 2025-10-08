{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.web.app')

@section('title', 'Tài khoản - Ihandmade.com')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-medium">Quản lý tài khoản</span>
            </nav>

            <!-- Page Header -->
            <div class="grid grid-cols-12">
                <!-- Sidebar -->
                <div class="col-span-3 bg-gray-100 text-white p-4 space-y-2 min-h-[400px]">
                    <div class="w-full">
                        <span class="text-yellow-600 font-semibold text-xl text-center">XIN CHÀO: {{ $user->name }}</span>
                    </div>
                    <div
                        class="border  border-collapse rounded-lg px-4 py-2 bg-slate-800 hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-700">
                        <button class="w-full tab-btn text-center" data-tab="tab1">Đơn hàng</button>
                    </div>
                    <div
                        class="border  border-collapse rounded-lg px-4 py-2 bg-slate-800 hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-700">
                        <button class="w-full text-center tab-btn" data-tab="tab2">Tài khoản</button>
                    </div>
                </div>

                <!-- Content -->
                <div class="col-span-9 bg-white p-4 min-h-[400px]">
                    <!-- Quản lý đơn hàng -->
                    <div class="tab-content" id="tab1">
                        <h2 class="text-xl font-semibold mb-4">Quản lý đơn hàng</h2>
                        @if ($orders->isEmpty())
                            <p>Chưa có đơn hàng nào.</p>
                        @else
                            <div class="space-y-4">
                                @foreach ($orders as $order)
                                    <div class="border p-4 rounded shadow-sm bg-white">
                                        <div class="flex justify-between items-center mb-2">
                                            <div>
                                                <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
                                                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                                                <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
                                            </div>
                                            <div class="space-x-2">
                                                <!-- Nút theo dõi -->
                                                {{-- {{ route('orders.track', $order->id) }} --}}
                                                <a href=""
                                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Theo
                                                    dõi</a>

                                                <!-- Nút xem đơn -->
                                                {{-- {{ route('orders.show', $order->id) }} --}}
                                                <a href=""
                                                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">Xem
                                                    đơn</a>

                                                <!-- Nút hủy đơn -->
                                                {{-- {{ route('orders.cancel', $order->id) }} --}}
                                                <form action="" method="POST" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')"
                                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                                        Hủy đơn
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="tab-content hidden" id="tab2">Nội dung Tab 2</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const buttons = document.querySelectorAll('.tab-btn');
                const contents = document.querySelectorAll('.tab-content');

                // Hàm ẩn tất cả content
                function hideAllTabs() {
                    contents.forEach(content => content.classList.add('hidden'));
                }

                // Hàm hiển thị tab theo id
                function showTab(tabId) {
                    hideAllTabs();
                    const target = document.getElementById(tabId);
                    if (target) {
                        target.classList.remove('hidden');
                    }
                }

                // Bắt sự kiện click các nút tab
                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const tab = btn.getAttribute('data-tab');
                        localStorage.setItem('activeTab', tab); // 👉 Lưu tab vào localStorage
                        showTab(tab);
                    });
                });

                // Khi trang load lại, kiểm tra tab nào được lưu
                const savedTab = localStorage.getItem('activeTab') || 'tab1'; // Mặc định là tab1
                showTab(savedTab);
            });
        </script>
    @endpush

@endpush
