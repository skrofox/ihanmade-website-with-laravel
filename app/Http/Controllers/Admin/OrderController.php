<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'items.product'])
            ->orderBy('created_at', 'desc');

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('status', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('email', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return view('admin.page.orders', compact('orders'));
    }

    public function pending(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'items.product'])
            ->where('status', 'placed')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('email', 'like', "%{$keyword}%");
                    });
            });
        }

        $orders = $query->paginate(15);

        return view('admin.page.orders', compact('orders'));
    }

    public function processing(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'items.product'])
            ->whereIn('status', ['paid', 'fulfilling', 'shipped'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('email', 'like', "%{$keyword}%");
                    });
            });
        }

        $orders = $query->paginate(15);

        return view('admin.page.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'shippingAddress',
            'billingAddress',
            'items.product',
            'items.variant'
        ])->findOrFail($id);

        return view('admin.page.order_detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:placed,paid,fulfilling,shipped,completed,cancelled',
            'cancel_reason' => 'nullable|string|max:255'
        ]);

        $order = Order::with('items.variant.stockItems')->findOrFail($id);

        $order->status = $request->status;

        if ($request->status === 'cancelled' && $request->cancel_reason) {
            $order->cancel_reason = $request->cancel_reason;
            $order->cancelled_at = now();
        } elseif ($request->status === 'paid') {
            $order->paid_at = now();
        } elseif ($request->status === 'completed') {
            $order->cancelled_at = null;
            $order->cancel_reason = null;

            // Trừ tồn kho trong stock_items
            foreach ($order->items as $item) {
                $variant = $item->variant;

                if ($variant && $variant->stockItems->count()) {
                    // ví dụ trừ ở kho đầu tiên, hoặc chọn theo logic riêng
                    $stockItem = $variant->stockItems->first();
                    $stockItem->on_hand -= $item->qty;
                    if ($stockItem->on_hand < 0) {
                        $stockItem->on_hand = 0; // tránh âm
                    }
                    $stockItem->save();
                }
            }
        }

        $order->save();

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // Delete order items first
            $order->items()->delete();

            // Delete the order
            $order->delete();

            DB::commit();

            return back()->with('success', 'Xóa đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi xóa đơn hàng!');
        }
    }

    public function search(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'items.product'])
            ->orderBy('created_at', 'desc');

        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('status', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('email', 'like', "%{$keyword}%");
                    });
            });
        }

        $orders = $query->paginate(15);

        return view('admin.page.orders', compact('orders'));
    }
}
