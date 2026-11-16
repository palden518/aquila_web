<x-mail::message>
# New online order {{ $order->order_number }}

| | |
|---|---|
| **Customer** | {{ $order->customer_name }} |
| **Email** | {{ $order->customer_email }} |
| **Phone** | {{ $order->customer_phone }} |
| **Pickup** | {{ $order->pickup_time->format('l j F, g:i A') }} |
| **Payment** | {{ $order->paymentLabel() }} |
@if ($order->cardPayment()?->transaction_ref)
| **Stripe reference** | {{ $order->cardPayment()->transaction_ref }} |
@endif

<x-mail::table>
| Item | Qty | Price |
| :--- | :-: | ---: |
@foreach ($order->items as $item)
| {{ $item->item_name }} | {{ $item->quantity }} | ${{ number_format($item->subtotal, 2) }} |
@endforeach
@foreach ($order->addons as $addon)
| {{ $addon->addon_name }} (add-on) | {{ $addon->quantity }} | {{ (float) $addon->subtotal > 0 ? '$' . number_format($addon->subtotal, 2) : 'Free' }} |
@endforeach
| **Total** | | **${{ number_format($order->total, 2) }}** |
</x-mail::table>

@if ($order->notes)
**Customer notes:** {{ $order->notes }}
@endif

<x-mail::button :url="route('admin.orders.show', $order)">
Open in admin panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
