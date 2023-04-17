<x-mail::message>
# From : {{$ticket->email}}

    {{ $ticket->message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
