@extends('layouts.admin.app')
@section('header', 'Kho hàng đã xóa')
@section('title', 'Kho hàng đã xóa')
@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Kho hàng đã xóa</h2>
            <a href="{{ route('warehouse_index') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Quay lại danh sách
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã kho
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tên kho
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Địa chỉ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày xóa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($warehouses as $warehouse)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $warehouse->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $warehouse->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $warehouse->address_text }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $warehouse->deleted_at->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <form action="{{ route('warehouse_restore', $warehouse->id) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        Khôi phục
                                    </button>
                                </form>
                                <form action="{{ route('warehouse_force_delete', $warehouse->id) }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn kho hàng này? Hành động này không thể hoàn tác!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Xóa vĩnh viễn
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Không có kho hàng nào bị xóa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($warehouses->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $warehouses->links('pagination.page_custom') }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>
<script>
setTimeout(function() {
    document.getElementById('success-message').style.display = 'none';
}, 3000);
</script>
@endif

@if(session('error'))
<div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('error') }}
</div>
<script>
setTimeout(function() {
    document.getElementById('error-message').style.display = 'none';
}, 3000);
</script>
@endif
@endsection
