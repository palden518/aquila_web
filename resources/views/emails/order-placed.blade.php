<x-mail::message>
# Thanks, {{ $order->customer_name }} — we've got your order

@if ($order->paymentState() === 'paid')
Your order **{{ $order->order_number }}** has been received and your card payment is confirmed. Nothing more to pay. Just collect it at the time below.
@else
Your order **{{ $order->order_number }}** has been received. Please pay when you collect it.
@endif

**Pickup:** {{ $order->pickup_time->format('l j F, g:i A') }}

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
**Your notes:** {{ $order->notes }}
@endif

<x-mail::button :url="route('orders.confirmation', ['order' => $order->order_number])">
View your order
</x-mail::button>

If anything needs to change, just call us and quote your order number.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
