<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
        return view("home", compact("newProducts"));
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
        return view("components.web.detail", compact("product"));
    }

    public function info_variant($id)
    {
        $variant = ProductVariant::with(['prices', 'stockItems'])->findOrFail($id);
        $price = $variant->currentPrice ? $variant->currentPrice->effective_price : 0;
        
        // Debug: Log thông tin variant
        Log::info('Variant ID: ' . $id);
        Log::info('Stock Items Count: ' . $variant->stockItems->count());
        
        // Tính available stock (on_hand - reserved) thay vì chỉ on_hand
        $stock = $variant->stockItems->sum(function($stockItem) {
            $available = $stockItem->on_hand - $stockItem->reserved;
            Log::info('Stock Item - On Hand: ' . $stockItem->on_hand . ', Reserved: ' . $stockItem->reserved . ', Available: ' . $available);
            return $available;
        });
        
        Log::info('Total Available Stock: ' . $stock);
        
        return response()->json([
            'price' => $price,
            'stock' => max(0, $stock), // Đảm bảo stock không âm
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
                'stock_items' => $variant->stockItems->map(function($item) {
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
}
