<?php

namespace App\Http\Controllers;

use App\Mail\ReceivedTicketMail;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function index()
    {
        $pending = Ticket::where('status','pending')->count();
        $resolved = Ticket::where('status','resolved')->count();
        $rejected = Ticket::where('status','rejected')->count();
        $total = Ticket::count();
        return response()->json(['tickets' => Ticket::orderBy('updated_at','desc')->get()->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'branch' => $ticket->branch->name,
                'email' => $ticket->email,
                'priority' => $ticket->priority,
                'status' => $ticket->status

            ];
        }),'pending' => $pending,'resolved' => $resolved,'rejected' => $rejected,'total' => $total]) ;
    }
    public function create(Request $request): \Illuminate\Http\JsonResponse
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

       Mail::to($request->email)->send(new ReceivedTicketMail($ticket));

       return response()->json($ticket);
    }
    public function show(Ticket $ticket): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'email' => $ticket->email,
            'title' => $ticket->title,
            'message' => $ticket->message,
            'status' => $ticket->status,
            'branch' => $ticket->branch->name,
            'category' => $ticket->category->name,
            'priority' => $ticket->priority,
            'help_topic' => $ticket->help_topic->name
        ]);
    }

    public function getPendingTicket(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Ticket::query()->where('status','=','pending')->count());
    }
    public function check_ticket(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'ticket_number' => ['required','exists:tickets'],
        ]);
        $passCheck = Ticket::where(['ticket_number' => $request->ticket_number ])->first();

        if(!$passCheck) {
            return response()->json(['errors' => [
                'ticket_number' => ['The select email do not correspond with the selected ticket number ']
            ]],422);
        }

        return response()->json($passCheck);
    }
}
