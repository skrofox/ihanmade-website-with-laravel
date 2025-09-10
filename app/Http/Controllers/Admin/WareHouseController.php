<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WareHouse::active();

        // Tìm kiếm theo code hoặc name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        $warehouses = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.page.warehouse.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouse = new WareHouse();
        $warehouse->code = WareHouse::generateCode();

        return view('admin.page.warehouse.create', compact('warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'code' => 'required|string|max:50|unique:ware_houses,code',
            'name' => 'required|string|max:150',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ], [
            'code.required' => 'Vui lòng nhập mã kho',
            'code.unique' => 'Mã kho đã tồn tại',
            'code.max' => 'Mã kho không được vượt quá 50 ký tự',
            'name.required' => 'Vui lòng nhập tên kho',
            'name.max' => 'Tên kho không được vượt quá 150 ký tự',
        ]);

        try {
            // Xử lý địa chỉ
            $address = [];
            if ($request->filled('street')) $address['street'] = $request->street;
            if ($request->filled('city')) $address['city'] = $request->city;
            if ($request->filled('state')) $address['state'] = $request->state;
            if ($request->filled('zip_code')) $address['zip_code'] = $request->zip_code;
            if ($request->filled('country')) $address['country'] = $request->country;

            // Tạo warehouse
            $warehouse = WareHouse::create([
                'code' => $request->code,
                'name' => $request->name,
                'address' => empty($address) ? null : $address,
            ]);

            return redirect()
                ->route('warehouse_index')
                ->with('success', 'Tạo kho hàng thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo kho hàng: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $warehouse = WareHouse::with('stockItems')->findOrFail($id);
        return view('admin.page.warehouse.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warehouse = WareHouse::findOrFail($id);
        return view('admin.page.warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $warehouse = WareHouse::findOrFail($id);

        // Validation
        $request->validate([
            'code' => 'required|string|max:50|unique:warehouses,code,' . $id,
            'name' => 'required|string|max:150',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ], [
            'code.required' => 'Vui lòng nhập mã kho',
            'code.unique' => 'Mã kho đã tồn tại',
            'code.max' => 'Mã kho không được vượt quá 50 ký tự',
            'name.required' => 'Vui lòng nhập tên kho',
            'name.max' => 'Tên kho không được vượt quá 150 ký tự',
        ]);

        try {
            // Xử lý địa chỉ
            $address = [];
            if ($request->filled('street')) $address['street'] = $request->street;
            if ($request->filled('city')) $address['city'] = $request->city;
            if ($request->filled('state')) $address['state'] = $request->state;
            if ($request->filled('zip_code')) $address['zip_code'] = $request->zip_code;
            if ($request->filled('country')) $address['country'] = $request->country;

            // Cập nhật warehouse
            $warehouse->update([
                'code' => $request->code,
                'name' => $request->name,
                'address' => empty($address) ? null : $address,
            ]);

            return redirect()
                ->route('warehouse_index')
                ->with('success', 'Cập nhật kho hàng thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật kho hàng: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $warehouse = WareHouse::findOrFail($id);

            // Kiểm tra xem có stock items nào không
            if ($warehouse->stockItems()->count() > 0) {
                return redirect()
                    ->route('warehouse_index')
                    ->with('error', 'Không thể xóa kho hàng này vì còn sản phẩm trong kho!');
            }

            $warehouse->delete();

            return redirect()
                ->route('warehouse_index')
                ->with('success', 'Xóa kho hàng thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('warehouse_index')
                ->with('error', 'Có lỗi xảy ra khi xóa kho hàng: ' . $e->getMessage());
        }
    }

    /**
     * Show trashed warehouses
     */
    public function trash()
    {
        $warehouses = WareHouse::onlyTrashed()->paginate(15);
        return view('admin.page.warehouse.trash', compact('warehouses'));
    }

    /**
     * Restore trashed warehouse
     */
    public function restore(string $id)
    {
        try {
            $warehouse = WareHouse::onlyTrashed()->findOrFail($id);
            $warehouse->restore();

            return redirect()
                ->route('warehouse_trash')
                ->with('success', 'Khôi phục kho hàng thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('warehouse_trash')
                ->with('error', 'Có lỗi xảy ra khi khôi phục kho hàng: ' . $e->getMessage());
        }
    }

    /**
     * Force delete warehouse
     */
    public function forceDelete(string $id)
    {
        try {
            $warehouse = WareHouse::onlyTrashed()->findOrFail($id);
            $warehouse->forceDelete();

            return redirect()
                ->route('warehouse_trash')
                ->with('success', 'Xóa vĩnh viễn kho hàng thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('warehouse_trash')
                ->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn kho hàng: ' . $e->getMessage());
        }
    }
}
