<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductVariant::with('product');

        // Tìm kiếm theo SKU hoặc barcode
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $variants = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.page.variant', compact('variants'));
    }

    public function detail($id)
    {
        $variant = ProductVariant::with('product')->findOrFail($id);
        return view('admin.page.variant.detail_variant', compact('variant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.page.variant.create_variant', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'barcode' => 'nullable|string|max:100|unique:product_variants,barcode',
            'option_keys' => 'nullable|array',
            'option_values' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'sku.required' => 'Vui lòng nhập SKU',
            'sku.unique' => 'SKU đã tồn tại',
            'sku.max' => 'SKU không được vượt quá 100 ký tự',
            'barcode.unique' => 'Mã vạch đã tồn tại',
            'barcode.max' => 'Mã vạch không được vượt quá 100 ký tự',
            'price.required' => 'Vui lòng nhập giá bán',
            'price.min' => 'Giá biến thể phải lớn hơn 0',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        try {
            // Xử lý option_value từ arrays
            $optionValue = null;
            if ($request->has('option_keys') && $request->has('option_values')) {
                $keys = $request->option_keys;
                $values = $request->option_values;
                $optionValue = [];

                for ($i = 0; $i < count($keys); $i++) {
                    if (!empty(trim($keys[$i])) && !empty(trim($values[$i]))) {
                        $optionValue[trim($keys[$i])] = trim($values[$i]);
                    }
                }

                // Nếu không có option nào hợp lệ thì set null
                if (empty($optionValue)) {
                    $optionValue = null;
                }
            }


            // Tạo product variant
            $variant = ProductVariant::create([
                'product_id' => $request->product_id,
                'sku' => $request->sku . '-' . Carbon::now()->getTimestamp(),
                'barcode' => $request->barcode ?: null,
                'option_value' => $optionValue,
                'status' => $request->status,
            ]);

            $price = Price::create([
                'variant_id' => $variant->id,
                'list_priced' => $request->price,
            ]);

            return redirect()
                ->route('variant_index')
                ->with('success', 'Tạo biến thể sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo biến thể: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $variant = ProductVariant::with('prices')->findOrFail($id);
        $products = Product::all();
        // dd($variant);
        return view('admin.page.variant.edit_variant', compact('variant', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $variant = ProductVariant::findOrFail($id);

        // Validation
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $id,
            'barcode' => 'nullable|string|max:100|unique:product_variants,barcode,' . $id,
            'option_keys' => 'nullable|array',
            'option_values' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'sku.required' => 'Vui lòng nhập SKU',
            'sku.unique' => 'SKU đã tồn tại',
            'sku.max' => 'SKU không được vượt quá 100 ký tự',
            'barcode.unique' => 'Mã vạch đã tồn tại',
            'barcode.max' => 'Mã vạch không được vượt quá 100 ký tự',
            'price.required' => 'Chưa nhập giá bán',
            'price.min' => 'Giá biến thể phải lớn hơn 0',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);

        try {
            // Xử lý option_value từ arrays
            $optionValue = null;
            if ($request->has('option_keys') && $request->has('option_values')) {
                $keys = $request->option_keys;
                $values = $request->option_values;
                $optionValue = [];

                for ($i = 0; $i < count($keys); $i++) {
                    if (!empty(trim($keys[$i])) && !empty(trim($values[$i]))) {
                        $optionValue[trim($keys[$i])] = trim($values[$i]);
                    }
                }

                // Nếu không có option nào hợp lệ thì set null
                if (empty($optionValue)) {
                    $optionValue = null;
                }
            }

            // Cập nhật product variant
            $variant->update([
                'product_id' => $request->product_id,
                'sku' => $request->sku,
                'barcode' => $request->barcode ?: null,
                'option_value' => $optionValue,
                'status' => $request->status,
            ]);

            $price = Price::where('variant_id', $variant->id)->first();

            if (!$price) {
                $price = Price::create([
                    'variant_id' => $variant->id,
                    'list_priced' => $request->price,
                ]);
            } else {
                $price->update([
                    'list_priced' => $request->price,
                ]);
            }

            return redirect()
                ->route('variant_index')
                ->with('success', 'Cập nhật biến thể sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật biến thể: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            $variant->delete();

            return redirect()
                ->route('variant_index')
                ->with('success', 'Xóa biến thể sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('variant_index')
                ->with('error', 'Có lỗi xảy ra khi xóa biến thể: ' . $e->getMessage());
        }
    }
}
