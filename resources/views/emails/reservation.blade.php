<x-mail::message>
# New table reservation

A new table reservation has come in through the website.

| | |
|---|---|
| **Name** | {{ $reservation->name }} |
| **Email** | {{ $reservation->email }} |
| **Phone** | {{ $reservation->phone }} |
| **Date** | {{ $reservation->reservation_date->format('d M Y') }} |
| **Time** | {{ \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('g:i A') }} |
| **Party size** | {{ $reservation->party_size }} |

@if ($reservation->notes)
**Notes:**

{{ $reservation->notes }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
