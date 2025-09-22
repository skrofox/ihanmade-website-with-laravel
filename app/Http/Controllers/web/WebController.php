<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebController extends Controller
{
    //
    public function index()
    {
        $newProducts =  Product::with(['variants.currentPrice'])
            ->orderBy("id", "desc")
            ->take(4)
            ->get();
        

        //lay sp theo category
        $category = Category::find(1);
        $productshandmade = $category->products;
        //

        //get product category='handmade'
        return view("home", compact("newProducts", 'productshandmade'));
    }

    public function detail($slug)
    {
        $product = Product::with([
            'variants.stockItems',
            'variants.prices',
            'images',
            'categories'
        ])->where("slug", $slug)->first();
        // $recommendProduct = Product::where()
        return view("detail", compact("product"));
    }

    public function info_variant($id)
    {
        $variant = ProductVariant::with(['prices', 'stockItems'])
            ->where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$variant) {
            return response()->json([
                'error' => 'Variant not found or inactive'
            ], 404);
        }

        $price = $variant->prices->first()->list_priced;

        $variant_stock = $variant->stockItems->sum('on_hand');

        if ($variant_stock == 0) {
            $status = 'Hết hàng';
        } else {
            $status = 'Còn hàng';
        }

        return response()->json([
            'id'    => $variant->id,
            'sku'   => $variant->sku,
            'price' => number_format($price) . ' VND',
            'variant_status' => $status,
        ]);
    }


    public function debugStock()
    {
        $variants = ProductVariant::with(['stockItems', 'product'])->get();
        $stockItems = \App\Models\StockItem::all();

        $debug = [
            'total_variants' => $variants->count(),
            'total_stock_items' => $stockItems->count(),
            'variants' => [],
            'stock_items' => []
        ];

        foreach ($variants as $variant) {
            $debug['variants'][] = [
                'id' => $variant->id,
                'product_name' => $variant->product->name ?? 'N/A',
                'option_value' => $variant->option_value,
                'stock_items_count' => $variant->stockItems->count(),
                'stock_items' => $variant->stockItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'on_hand' => $item->on_hand,
                        'reserved' => $item->reserved,
                        'available' => $item->on_hand - $item->reserved
                    ];
                })
            ];
        }

        foreach ($stockItems as $item) {
            $debug['stock_items'][] = [
                'id' => $item->id,
                'variant_id' => $item->variant_id,
                'on_hand' => $item->on_hand,
                'reserved' => $item->reserved,
                'available' => $item->on_hand - $item->reserved
            ];
        }

        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'LIKE', '%' . $query . '%')->get();

        return view('search', compact('products', 'query'));
    }
}
