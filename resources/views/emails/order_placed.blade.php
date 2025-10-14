@component('mail::message')
    # Cảm ơn bạn đã đặt hàng, {{ $order->shipping_full_name ?? ($order->full_name ?? 'Khách hàng') }}!

    Đơn hàng của bạn đã được ghi nhận với mã: **#{{ $order->id }}**
    Ngày: {{ $order->created_at->format('d/m/Y H:i') }}

    @component('mail::table')
        | Sản phẩm | Đơn giá | Số lượng | Tổng |
        | --- | ---: | :---: | ---: |
        @foreach ($order->items as $item)
            | {{ $item->name }} |
            {{ number_format($item->unit_price ?? ($item->unit_price_amount ?? ($item->price ?? 0)), 0, ',', '.') }}đ |
            {{ $item->qty }} | {{ number_format($item->subtotal ?? $item->qty * ($item->unit_price ?? 0), 0, ',', '.') }}đ
            |
        @endforeach
    @endcomponent

    @component('mail::panel')
        <strong>Tóm tắt thanh toán</strong><br>
        Tạm tính:
        {{ number_format($order->subtotal ?? $order->items->sum(fn($i) => $i->subtotal ?? $i->qty * ($i->unit_price ?? 0)), 0, ',', '.') }}đ<br>
        Phí vận chuyển: {{ number_format($order->shipping_total ?? 0, 0, ',', '.') }}đ<br>
        @if (($order->discount_total ?? 0) > 0)
            Giảm giá: -{{ number_format($order->discount_total, 0, ',', '.') }}đ<br>
        @endif
        <strong>Tổng: {{ number_format($order->grand_total ?? ($order->total ?? 0), 0, ',', '.') }}đ</strong>
    @endcomponent

    @component('mail::button', ['url' => route('orders.show', $order->id), 'color' => 'primary'])
        Xem chi tiết đơn hàng
    @endcomponent

    Thông tin giao hàng:
    {{ $order->shipping_address ?? ($order->address ?? '-') }}
    {{ $order->shipping_full_name ?? ($order->full_name ?? '') }} — {{ $order->shipping_phone ?? ($order->phone ?? '') }}

    Nếu cần hỗ trợ, vui lòng phản hồi email này hoặc liên hệ hotline.

    Trân trọng,
    Ihandmade
@endcomponent
