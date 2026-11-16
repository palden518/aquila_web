<x-mail::message>
# New contact message

Someone has submitted the Contact Us form on the website.

| | |
|---|---|
| **Name** | {{ $contactMessage->name }} |
| **Email** | {{ $contactMessage->email }} |
| **Phone** | {{ $contactMessage->phone ?: '—' }} |
| **Subject** | {{ $contactMessage->subject ?: '—' }} |

**Message:**

{{ $contactMessage->message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
