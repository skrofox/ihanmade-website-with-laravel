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
                                                @switch($order->status)
                                                    @case('placed')
                                                        <p class="text-green-600"><strong class="text-black">Trạng thái:</strong> Đã
                                                            Đặt</p>
                                                    @break

                                                    @case('fulfilling')
                                                        <p class="text-yellow-400"><strong class="text-black">Trạng thái:</strong>
                                                            Đang chuẩn bị</p>
                                                    @break

                                                    @case('shipped')
                                                        <p class="text-yellow-300"><strong class="text-black">Trạng thái:</strong>
                                                            Đang giao</p>
                                                    @break

                                                    @case('completed')
                                                        <p class="text-green-500"><strong class="text-black">Trạng thái:</strong> Đã
                                                            nhận</p>
                                                    @break

                                                    @case('paid')
                                                        <p class="text-green-400"><strong class="text-black">Trạng thái:</strong> Đã
                                                            thanh toán</p>
                                                    @break

                                                    @case('cancelled')
                                                        <p class="text-red-500"><strong class="text-black">Trạng thái:</strong> Đã
                                                            hủy</p>
                                                    @break

                                                    @default
                                                        <p><strong>Trạng thái:</strong class="text-black">
                                                            {{ $order->status ?? 'Không xác định' }}</p>
                                                @endswitch

                                            </div>
                                            <div class="flex space-x-2">
                                                <!-- Nút theo dõi -->
                                                {{-- {{ route('orders.track', $order->id) }} --}}
                                                {{-- <a href=""
                                                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Theo
                                                    dõi</a> --}}

                                                <!-- Nút xem đơn -->

                                                <a href="{{ route('orders.show', $order->id) }}" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="px-3 py-1 bg-slate-600 text-white rounded hover:bg-slate-700 transition">Xem
                                                    đơn</a>

                                                @if ($order->created_at->diffInDays(now()) >= 3)
                                                    <form action="{{ route('order.completed') }}" method="post">
                                                        <button type="submit"
                                                            class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                                            Đã Nhận
                                                        </button>
                                                    </form>
                                                @else
                                                    @if ($order->status != 'completed' && $order->status != 'cancelled' && $order->status != 'shipped')
                                                        <button type="button" onclick="showCancelForm({{ $order->id }})"
                                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                                            Hủy đơn
                                                        </button>

                                                        <!-- Modal (ẩn ban đầu) -->
                                                        <div id="cancelModal-{{ $order->id }}"
                                                            class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
                                                            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                                                                <h3 class="text-lg font-semibold mb-4 text-gray-800">Lý do
                                                                    hủy
                                                                    đơn</h3>
                                                                <form action="{{ route('orders.cancel', $order->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return validateReason({{ $order->id }})">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <textarea id="cancelReason-{{ $order->id }}" name="reason" rows="3" required
                                                                        class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-red-200"
                                                                        placeholder="Nhập lý do hủy đơn..."></textarea>

                                                                    <div class="flex justify-end gap-3 mt-4">
                                                                        <button type="button"
                                                                            onclick="closeCancelForm({{ $order->id }})"
                                                                            class="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                                                                            Hủy
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                                                            Xác nhận hủy
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <!-- Quản lý tài khoản -->
                    <div class="tab-content hidden" id="tab2">
                        <h2 class="text-xl font-semibold mb-4">Thông tin tài khoản</h2>
                        @if (session('success'))
                            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Thông tin cá nhân -->
                        <div class="bg-white p-4 rounded shadow mb-6">
                            <h3 class="text-lg font-semibold mb-2">Thông tin người dùng</h3>
                            <p><strong>Họ tên:</strong> {{ auth()->user()->name }}</p>
                            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

                            <!-- Sửa tên -->

                            <form action="{{ route('user.updateName') }}" method="POST" class="mt-4 space-y-2">
                                @csrf
                                <label for="name" class="block text-sm font-medium text-gray-700">Cập nhật tên
                                    mới:</label>
                                <input type="text" name="name" id="name" class="w-full border rounded p-2"
                                    required>
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cập nhật tên</button>
                            </form>
                        </div>

                        <!-- Đổi mật khẩu -->
                        <div class="bg-white p-4 rounded shadow mb-6">
                            <h3 class="text-lg font-semibold mb-2">Đổi mật khẩu</h3>
                            <form action="{{ route('user.changePassword') }}" method="POST" class="space-y-2">
                                @csrf
                                <input type="password" name="current_password" placeholder="Mật khẩu hiện tại"
                                    class="w-full border rounded p-2" required>
                                <input type="password" name="new_password" placeholder="Mật khẩu mới"
                                    class="w-full border rounded p-2" required>
                                <input type="password" name="new_password_confirmation"
                                    placeholder="Xác nhận mật khẩu mới" class="w-full border rounded p-2" required>
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Cập nhật mật
                                    khẩu</button>
                            </form>
                        </div>

                        <!-- Danh sách địa chỉ -->
                        <div class="bg-white p-4 rounded shadow">
                            <h3 class="text-lg font-semibold mb-2">Danh sách địa chỉ</h3>

                            @foreach ($user->addresses as $address)
                                <div class="border p-3 rounded mb-3 flex justify-between items-start">
                                    <div>
                                        <p class="pt-2"><strong>Tên người nhận:</strong> {{ $address->full_name }}</p>
                                        <p class="pt-2"><strong>Địa chỉ:</strong> {{ $address->line1 }}</p>
                                        <p class="pt-2"><strong>SĐT:</strong> {{ $address->phone }}</p>
                                        @if ($address->is_default_shipping == 1)
                                            <span
                                                class="inline-block mt-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                                Mặc định
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Nút chức năng -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                                        @unless ($address->is_default_shipping)
                                            <!-- Chọn làm mặc định -->
                                            <form action="{{ route('user.address.setDefault', $address->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="text-sm px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                                    Chọn làm mặc định
                                                </button>
                                            </form>
                                        @endunless

                                        <!-- Nút sửa -->
                                        <a href=""
                                            class="text-sm px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                            Sửa
                                        </a>

                                        <!-- Nút xóa -->
                                        <form action="{{ route('user.address.delete', $address->id) }}" method="POST"
                                            onsubmit="return confirm('Xóa địa chỉ này?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            @if ($user->addresses->count() < 3)
                                <button
                                    class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                                    onclick="openAddAddressModal()">
                                    + Thêm địa chỉ mới
                                </button>
                            @endif

                            <!-- Modal thêm địa chỉ -->
                            <div id="addAddressModal"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                <div class="bg-white w-full max-w-md p-6 rounded shadow">
                                    <h2 class="text-lg font-semibold mb-4">Thêm địa chỉ mới</h2>
                                    <form action="{{ route('user.address.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="text" name="fullname" placeholder="Tên người nhận"
                                            class="w-full border p-2 rounded" required>
                                        <input type="text" name="phone" placeholder="Số điện thoại"
                                            class="w-full border p-2 rounded" required>
                                        <input type="text" name="line1" placeholder="Địa chỉ"
                                            class="w-full border p-2 rounded" required>

                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="closeAddAddressModal()"
                                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Hủy</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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

            function openAddAddressModal() {
                document.getElementById('addAddressModal').classList.remove('hidden');
            }

            function closeAddAddressModal() {
                document.getElementById('addAddressModal').classList.add('hidden');
            }

            //script huy don
            function showCancelForm(orderId) {
                document.getElementById('cancelModal-' + orderId).classList.remove('hidden');
            }

            function closeCancelForm(orderId) {
                document.getElementById('cancelModal-' + orderId).classList.add('hidden');
            }

            function validateReason(orderId) {
                const reason = document.getElementById('cancelReason-' + orderId).value.trim();
                if (!reason) {
                    alert('Vui lòng nhập lý do hủy đơn.');
                    return false;
                }
                return confirm('Bạn có chắc chắn muốn hủy đơn này?');
            }
        </script>
    @endpush

@endpush
