<x-mail::message>
# Hi {{$ticket['email']}},
    {{$ticket['reply'] ?? "Your [Issue, Ticket ID: {$ticket['ticket_number']} has been {$ticket['status']}]."}}
    Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
