<x-mail::message>

Hi {{ $reminder->user->name ?? (config('app.name') . ' User') }},

This is a friendly reminder for the upcoming event:

## {{ $reminder->title }}

- **Description:** {{ $reminder->description }}

- **Event At:** {{ $eventAtFormatter }}

We hope to see you there! If you have any questions or need more information, feel free to contact us.

Thanks,<br>

{{ config('app.name') }}
</x-mail::message>
