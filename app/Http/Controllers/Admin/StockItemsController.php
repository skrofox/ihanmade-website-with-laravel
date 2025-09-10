<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use App\Models\WareHouse;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('keyword');
        $warehouse = $request->input('warehouse');
        $stockLevel = $request->input('stock_level');

        $query = StockItem::with(['warehouse', 'variant.product']);

        // Tìm kiếm
        if ($search) {
            $query->search($search);
        }

        // Lọc theo warehouse
        if ($warehouse) {
            $query->byWarehouse($warehouse);
        }

        // Lọc theo mức stock
        if ($stockLevel) {
            switch ($stockLevel) {
                case 'low':
                    $query->lowStock();
                    break;
                case 'out':
                    $query->outOfStock();
                    break;
                case 'high':
                    $query->whereRaw('on_hand >= max_stock_level');
                    break;
            }
        }

        $stockItems = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $warehouses = WareHouse::all();
        $stockLevels = [
            'low' => 'Thấp',
            'normal' => 'Bình thường',
            'high' => 'Cao',
            'out' => 'Hết hàng'
        ];

        return view('admin.page.stock_items.index', compact('stockItems', 'warehouses', 'stockLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = WareHouse::all();
        $variants = ProductVariant::with('product')->active()->get();
        
        return view('admin.page.stock_items.create', compact('warehouses', 'variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ware_house_id' => 'required|exists:ware_houses,id',
            'variant_id' => 'required|exists:product_variants,id',
            'on_hand' => 'required|integer|min:0',
            'reserved' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0',
        ], [
            'ware_house_id.required' => 'Vui lòng chọn kho hàng',
            'ware_house_id.exists' => 'Kho hàng không tồn tại',
            'variant_id.required' => 'Vui lòng chọn biến thể sản phẩm',
            'variant_id.exists' => 'Biến thể sản phẩm không tồn tại',
            'on_hand.required' => 'Vui lòng nhập số lượng tồn kho',
            'on_hand.integer' => 'Số lượng phải là số nguyên',
            'on_hand.min' => 'Số lượng không được âm',
        ]);

        // Kiểm tra xem đã tồn tại stock item cho warehouse và variant này chưa
        $existingStock = StockItem::where('ware_house_id', $request->ware_house_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingStock) {
            return back()->withErrors(['variant_id' => 'Đã tồn tại stock item cho biến thể này trong kho hàng này']);
        }

        // Kiểm tra reserved không được lớn hơn on_hand
        if ($request->reserved && $request->reserved > $request->on_hand) {
            return back()->withErrors(['reserved' => 'Số lượng đặt trước không được lớn hơn số lượng tồn kho']);
        }

        try {
            DB::beginTransaction();

            $stockItem = StockItem::create([
                'ware_house_id' => $request->ware_house_id,
                'variant_id' => $request->variant_id,
                'on_hand' => $request->on_hand,
                'reserved' => $request->reserved ?? 0,
                'min_stock_level' => $request->min_stock_level ?? 0,
                'max_stock_level' => $request->max_stock_level ?? 0,
            ]);

            DB::commit();

            return redirect()->route('stock_index')->with('success', 'Tạo stock item thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stockItem = StockItem::with(['warehouse', 'variant.product'])->findOrFail($id);
        
        return view('admin.page.stock_items.show', compact('stockItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stockItem = StockItem::findOrFail($id);
        $warehouses = WareHouse::all();
        $variants = ProductVariant::with('product')->active()->get();
        
        return view('admin.page.stock_items.edit', compact('stockItem', 'warehouses', 'variants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stockItem = StockItem::findOrFail($id);

        $request->validate([
            'ware_house_id' => 'required|exists:ware_houses,id',
            'variant_id' => 'required|exists:product_variants,id',
            'on_hand' => 'required|integer|min:0',
            'reserved' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0',
        ]);

        // Kiểm tra xem đã tồn tại stock item khác cho warehouse và variant này chưa
        $existingStock = StockItem::where('ware_house_id', $request->ware_house_id)
            ->where('variant_id', $request->variant_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingStock) {
            return back()->withErrors(['variant_id' => 'Đã tồn tại stock item cho biến thể này trong kho hàng này']);
        }

        // Kiểm tra reserved không được lớn hơn on_hand
        if ($request->reserved && $request->reserved > $request->on_hand) {
            return back()->withErrors(['reserved' => 'Số lượng đặt trước không được lớn hơn số lượng tồn kho']);
        }

        try {
            DB::beginTransaction();

            $stockItem->update([
                'ware_house_id' => $request->ware_house_id,
                'variant_id' => $request->variant_id,
                'on_hand' => $request->on_hand,
                'reserved' => $request->reserved ?? 0,
                'min_stock_level' => $request->min_stock_level ?? 0,
                'max_stock_level' => $request->max_stock_level ?? 0,
            ]);

            DB::commit();

            return redirect()->route('stock_index')->with('success', 'Cập nhật stock item thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stockItem = StockItem::findOrFail($id);
        $stockItem->delete();

        return redirect()->route('stock_index')->with('success', 'Xóa stock item thành công');
    }

    /**
     * Hiển thị trang trash
     */
    public function trash()
    {
        $stockItems = StockItem::onlyTrashed()
            ->with(['warehouse', 'variant.product'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.page.stock_items.trash', compact('stockItems'));
    }

    /**
     * Khôi phục stock item từ trash
     */
    public function restore(string $id)
    {
        $stockItem = StockItem::onlyTrashed()->findOrFail($id);
        $stockItem->restore();

        return redirect()->route('stock_trash')->with('success', 'Khôi phục stock item thành công');
    }

    /**
     * Xóa vĩnh viễn stock item
     */
    public function forceDelete(string $id)
    {
        $stockItem = StockItem::onlyTrashed()->findOrFail($id);
        $stockItem->forceDelete();

        return redirect()->route('stock_trash')->with('success', 'Xóa vĩnh viễn stock item thành công');
    }

    /**
     * Tìm kiếm stock items
     */
    public function search(Request $request)
    {
        $search = $request->input('keyword');
        $stockItems = StockItem::with(['warehouse', 'variant.product'])
            ->search($search)
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.page.stock_items.index', compact('stockItems'));
    }

    /**
     * Điều chỉnh số lượng stock
     */
    public function adjustStock(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract',
            'reason' => 'nullable|string|max:255'
        ]);

        $stockItem = StockItem::findOrFail($id);
        
        if ($request->type === 'subtract' && $stockItem->on_hand < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng tồn kho không đủ để trừ'
            ], 400);
        }

        $stockItem->adjustStock($request->quantity, $request->type);

        return response()->json([
            'success' => true,
            'message' => 'Điều chỉnh stock thành công',
            'new_on_hand' => $stockItem->on_hand,
            'new_available' => $stockItem->available_stock
        ]);
    }

    /**
     * Đặt trước stock
     */
    public function reserveStock(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255'
        ]);

        $stockItem = StockItem::findOrFail($id);
        
        if (!$stockItem->reserveStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Không đủ stock để đặt trước'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đặt trước stock thành công',
            'new_reserved' => $stockItem->reserved,
            'new_available' => $stockItem->available_stock
        ]);
    }

    /**
     * Giải phóng stock đã đặt trước
     */
    public function releaseReservedStock(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255'
        ]);

        $stockItem = StockItem::findOrFail($id);
        
        if (!$stockItem->releaseReservedStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Không đủ stock đã đặt trước để giải phóng'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Giải phóng stock đặt trước thành công',
            'new_reserved' => $stockItem->reserved,
            'new_available' => $stockItem->available_stock
        ]);
    }
}
