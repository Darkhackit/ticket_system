<x-mail::message>
# Hi {{$ticket->email}},

    Thank you for reaching out. This is just a quick note to inform you that we received your message and have already started working on resolving your issue [Ticket ID: {{$ticket->ticket_number}}].
    Please you can use the Ticket ID to check the status of your ticket
    If you have any further questions or concerns, please let us know. We are available round-the-clock and always happy to help.

    Thanks,<br>
    {{ config('app.name') }}
    </x-mail::message>
