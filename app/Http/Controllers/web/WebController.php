<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebController extends Controller
{
    //

    public function account(){
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get();
        return view('account', compact('user', 'orders'));
    }
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
        $category = $product->categories()->first();
        // $recommendProducts = $category->products()->take(4)->get();
        if (!$category) {
            $recommendProducts = collect(); // Trống
        } else {
            // Lấy sản phẩm gợi ý từ cùng category (trừ chính sản phẩm hiện tại)
            $recommendProducts = $category->products()
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();
        }
        return view("detail", compact("product", "recommendProducts"));
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

        $priceModel = $variant->prices->first();

        $price = $priceModel ? $priceModel->list_priced : 0;


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

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'
            ], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['note' => null],
        );

        $variant = ProductVariant::with('currentPrice')->findOrFail($request->variantId);
        $priceUnit = $variant->currentPrice->effective_price;

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $request->variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->input('quantity');
            $cartItem->save();
        } else {
            $cartItem = new CartItem([
                'cart_id'   => $cart->id,
                'variant_id' => $request->variantId,
                'quantity' => $request->quantity,
                'unit_price_snapshot' => $priceUnit
            ]);
            $cartItem->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công.',
            'cart_count' => $cart->items()->sum('quantity')
        ]);
    }

    public function cart()
    {
        $cart = Cart::with(['items.variant', 'items.variant.currentPrice', 'items.variant.product', 'items.variant.product.images', 'items.variant.prices', 'items.variant.stockItems'])->where('user_id', Auth::id())->first();
        // $cartItems = $cart->items;
        $cartItems = $cart ? $cart->items : collect([]);
        // dd($cartItems);
        return view('cart', compact('cart', 'cartItems'));
    }


    public function updateCart(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);

        $valiated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->quantity = $valiated['quantity'];
        $item->save();

        return response()->json([
            'success' => true,
            'subtotal' => $item->subtotal(),
            'cart_total' => $item->cart->total,
        ]);
    }

    public function checkout()
    {
        $user = User::find(Auth::user()->id);
        $addresses = $user->addresses;
        $cart = Cart::with(['items.variant', 'items.variant.currentPrice', 'items.variant.product', 'items.variant.product.images', 'items.variant.prices'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        return view('checkout', compact('cart', 'addresses'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cod,momo,bank_transfer,credit_card',
            'notes' => 'nullable|string|max:1000',
            'agree_terms' => 'required|accepted'
        ]);

        $cart = Cart::with(['items.variant', 'items.variant.currentPrice', 'items.variant.product'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        try {
            DB::beginTransaction();

            // Tạo địa chỉ giao hàng
            $shippingAddress = Address::create([
                'user_id' => Auth::id(),
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'line1' => $request->address,
                'line2' => null,
                'ward' => null,
                'district' => null,
                'city' => null,
                'province' => null,
                'is_default_shipping' => false,
                'is_default_billing' => false,
            ]);

            // Tính toán tổng tiền
            $subtotal = $cart->total;
            $shippingCost = 0; // Không tính phí ship
            $discount = 0; // Có thể thêm logic tính discount sau
            $tax = 0; // Không tính thuế
            $grandTotal = $subtotal + $shippingCost - $discount + $tax;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'email' => $request->email ?: Auth::user()->email,
                'shipping_address_id' => $shippingAddress->id,
                'status' => 'placed',
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'shipping_total' => $shippingCost,
                'tax_total' => $tax,
                'grand_total' => $grandTotal,
                'notes' => $request->notes,
                'placed_at' => now(),
            ]);

            // Tạo các order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->variant->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'sku' => $cartItem->variant->sku,
                    'name' => $cartItem->variant->product->name . ' - ' . $cartItem->variant->option_value_text,
                    'qty' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price_snapshot,
                    'discount_total' => 0,
                    'tax_total' => 0,
                ]);
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            $cart->items()->delete();
            $cart->delete();


            DB::commit();

            return redirect()->route('checkout.success', $order)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!');
        }
    }

    public function checkoutSuccess(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'shippingAddress']);

        return view('checkout-success', compact('order'));
    }

    public function order()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(15);
        return view('order', compact('orders'));
    }

    public function removeCartItem($id)
    {
        try {
            $cart = Cart::where('user_id', Auth::id())->first();

            if ($cart) {
                $cartItems = CartItem::where('id', $id)
                    ->first();

                $cartItems->delete();

                // return response()->json([
                //     'success' => true,
                //     'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
                //     // 'cart_total' => $cartTotal
                // ]);
                return redirect()->route('cart.index')->with('success', 'Xóa sản phẩm khỏi giỏ hàng thành công');
            } else {
                // return response()->json([
                //     'success' => false,
                //     'message' => 'Giỏ hàng trống!',
                // ], 400);
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
            }
        } catch (\Throwable $th) {
            //throw $th;
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Xóa không thành công: ' . $th->getMessage(),
            // ], 400);
            return redirect()->route('cart.index')->with('error', 'Xóa không thành công: ' . $th->getMessage());
        }
    }

    public function categories(Request $request)
    {
        $dm = $request->query('dm');
        $categories = Category::all();
        $category = Category::where('slug', $dm)->first();
        if ($dm) {
            $category = Category::where('slug', $dm)->first();

            if (!$category) {
                abort(404, 'Danh mục không tồn tại.');
            }

            $products = $category->products()->paginate(12);
        } else {
            $products = Product::paginate(12);
        }
        return view('categories', compact('categories', 'products'));
    }
}
