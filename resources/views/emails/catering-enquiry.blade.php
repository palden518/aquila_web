<x-mail::message>
# New catering enquiry

A new catering / event enquiry has come in through the website.

| | |
|---|---|
| **Name** | {{ $enquiry->name }} |
| **Email** | {{ $enquiry->email }} |
| **Phone** | {{ $enquiry->phone }} |
| **Event date** | {{ $enquiry->event_date->format('d M Y') }} |
| **Guest count** | {{ $enquiry->guest_count }} |
| **Event type** | {{ $enquiry->event_type ? ucfirst(str_replace('_', ' ', $enquiry->event_type)) : '—' }} |
| **Package** | {{ $enquiry->package?->name ?? 'Not sure yet' }} |

@if ($enquiry->message)
**Message:**

{{ $enquiry->message }}
@endif

<x-mail::button :url="route('admin.catering-enquiries.show', $enquiry)">
View in admin panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
