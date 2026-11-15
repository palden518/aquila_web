<x-mail::message>
# New table reservation

A customer has requested a table through the website. It is waiting for you to confirm or cancel it.

<x-mail::table>
| | |
|:---|:---|
| **Booking reference** | #{{ $reservation->id }} |
| **Name** | {{ $reservation->name }} |
| **Email** | {{ $reservation->email }} |
| **Phone** | {{ $reservation->phone }} |
| **Date** | {{ $date }} |
| **Time** | {{ $time }} |
| **Party size** | {{ $reservation->party_size }} |
</x-mail::table>

@if ($reservation->notes)
**Special requests:**

{{ $reservation->notes }}
@endif

<x-mail::button :url="route('admin.reservations.show', $reservation)">
View in admin panel
</x-mail::button>

You can reply to this email to contact the customer directly.

{{ config('app.name') }}
</x-mail::message>
