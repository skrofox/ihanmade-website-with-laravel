<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng #{{ $order->id }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            color: #111827;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #FF8A00;
            font-size: 22px;
            margin-bottom: 12px;
        }

        h2 {
            font-size: 18px;
            color: #111827;
            margin-top: 24px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            font-size: 14px;
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        td.text-right {
            text-align: right;
        }

        td.text-center {
            text-align: center;
        }

        .panel {
            background-color: #fff8f1;
            border: 1px solid #FFE1C6;
            border-radius: 6px;
            padding: 16px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #FF8A00;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
            transition: background-color 0.2s ease-in-out;
        }

        .btn:hover {
            background-color: #e57800;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 24px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 16px;
            }

            table,
            th,
            td {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cảm ơn bạn đã đặt hàng, {{ $order->shipping_full_name ?? ($order->full_name ?? 'Khách hàng') }}!</h1>

        <p>
            Đơn hàng của bạn đã được ghi nhận với mã:
            <strong>#{{ $order->id }}</strong><br>
            Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
        </p>

        <h2>Chi tiết đơn hàng</h2>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-right">Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            {{ number_format($item->unit_price ?? ($item->unit_price_amount ?? ($item->price ?? 0)), 0, ',', '.') }}đ
                        </td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-right">
                            {{ number_format($item->subtotal ?? $item->qty * ($item->unit_price ?? 0), 0, ',', '.') }}đ
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="panel">
            <h3 style="margin-top:0;margin-bottom:8px;">Tóm tắt thanh toán</h3>
            <p style="margin:0;">Tạm tính:
                <strong>{{ number_format($order->subtotal ?? $order->items->sum(fn($i) => $i->subtotal ?? $i->qty * ($i->unit_price ?? 0)), 0, ',', '.') }}đ</strong>
            </p>
            <p style="margin:0;">Phí vận chuyển:
                <strong>{{ number_format($order->shipping_total ?? 0, 0, ',', '.') }}đ</strong>
            </p>
            @if (($order->discount_total ?? 0) > 0)
                <p style="margin:0;">Giảm giá:
                    <strong>-{{ number_format($order->discount_total, 0, ',', '.') }}đ</strong>
                </p>
            @endif
            <p style="margin-top:8px;font-size:16px;">
                <strong>Tổng thanh toán:
                    {{ number_format($order->grand_total ?? ($order->total ?? 0), 0, ',', '.') }}đ</strong>
            </p>
        </div>

        <div style="text-align:center;">
            <a href="{{ route('orders.show', $order->id) }}" class="btn">Xem chi tiết đơn hàng</a>
        </div>

        <h2>Thông tin giao hàng</h2>
        <p>
            <strong>Tên người nhận:</strong> {{ $order->shippingAddress->full_name ?? ($order->address->full_name ?? '-') }}<br>
            <strong>Số điện thoại:</strong>{{ $order->shippingAddress->phone ?? ($order->address->phone ?? '-') }}<br>
            <strong>Địa chỉ:</strong>{{ $order->shippingAddress->line1 }}
        </p>

        <p style="margin-top:24px;">
            Nếu cần hỗ trợ, vui lòng phản hồi email này hoặc liên hệ hotline của <strong>Ihandmade</strong>.
        </p>

        <div class="footer">
            <p>© {{ date('Y') }} Ihandmade. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>

</html>
