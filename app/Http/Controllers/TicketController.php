<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        return response()->json(Ticket::orderBy('created_at','desc')->get()->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'branch' => $ticket->branch->name,
                'email' => $ticket->email,
                'priority' => $ticket->priority,
                'status' => $ticket->status

            ];
        })) ;
    }
    public function create(Request $request)
    {
        $this->validate($request,[
            'value' => ['required'],
            'help_topic' => ['required'],
            'branch' => ['required'],
            'priority' => ['required'],
            'email' => ['required'],
            'title' => ['required']
        ]);
       $ticket = new Ticket();
       $ticket->branch_id = $request->branch['id'];
       $ticket->category_id = $request->category['id'];
       $ticket->help_topic_id = $request->help_topic['id'];
       $ticket->title = $request->title;
       $ticket->email = $request->email;
       $ticket->status = 'pending';
       $ticket->priority = $request->priority;
       $ticket->message = $request->value;
       $ticket->ticket_number = mt_rand();

       $ticket->save();

       return response()->json($ticket);
    }
    public function show(Ticket $ticket)
    {
        return response()->json([
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'email' => $ticket->email,
            'message' => $ticket->message,
            'status' => $ticket->status,
            'branch' => $ticket->branch->name,
            'category' => $ticket->category->name,
            'priority' => $ticket->priority,
            'help_topic' => $ticket->help_topic->name
        ]);
    }

    public function getPendingTicket()
    {
        return response()->json(Ticket::query()->where('status','=','pending')->count());
    }
}
