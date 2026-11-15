<x-mail::message>
# Thanks for your booking request, {{ $reservation->name }}

We have received your request for a table at The Aquila. Here are the details you gave us.

<x-mail::table>
| | |
|:---|:---|
| **Date** | {{ $date }} |
| **Time** | {{ $time }} |
| **Party size** | {{ $guests }} |
| **Booking reference** | #{{ $reservation->id }} |
</x-mail::table>

@if ($reservation->notes)
**Your special requests:**

{{ $reservation->notes }}
@endif

<x-mail::panel>
This is not a confirmed booking yet. Our team will check availability and get back to you by email or phone shortly.
</x-mail::panel>

If you need to change or cancel your request, call us on {{ $phone }} or reply to this email.

We look forward to seeing you.

The Aquila<br>
{{ $address }}
</x-mail::message>
