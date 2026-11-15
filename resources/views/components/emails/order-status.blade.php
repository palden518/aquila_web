<x-mail::message>
@if ($order->status === 'ready')
# Your order is ready, {{ $order->customer_name }}

Order **{{ $order->order_number }}** is ready for pickup.@if ($order->paymentState() !== 'paid') Please pay when you collect it.@endif
@else
# Order {{ $order->order_number }} has been cancelled

Hi {{ $order->customer_name }}, your order has been cancelled. If you weren't expecting this, please give us a call and we'll sort it out.
@endif

<x-mail::button :url="route('orders.confirmation', ['order' => $order->order_number])">
View your order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
