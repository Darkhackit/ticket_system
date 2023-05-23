<?php

namespace App\Http\Controllers;
use App\Mail\AutoRespond;
use App\Mail\ReceivedTicketMail;
use App\Mail\ReplyTicket;
use App\Models\Branch;
use App\Models\Category;
use App\Models\HelpTopic;
use App\Models\Image;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class TicketController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $q = \request('q');
        $tickets;
        $pending = Ticket::where('status','pending')->count();
        $resolved = Ticket::where('status','resolved')->count();
        $rejected = Ticket::where('status','rejected')->count();
        $total = Ticket::count();
        if ($q) {
            $tickets = Ticket::where('status',$q)->orderBy('updated_at','desc')->get()->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'branch' => $ticket->branch->name,
                    'email' => $ticket->email,
                    'priority' => $ticket->priority,
                    'status' => $ticket->status,
                    'file' => $ticket->images->first()?->url

                ];
            });
        }else {
            $tickets = Ticket::orderBy('updated_at','desc')->get()->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'branch' => $ticket->branch->name,
                    'email' => $ticket->email,
                    'priority' => $ticket->priority,
                    'status' => $ticket->status,
                    'file' => $ticket->images->first()?->url

                ];
            });
        }

        return response()->json(['tickets' => $tickets,'pending' => $pending,'resolved' => $resolved,'rejected' => $rejected,'total' => $total]) ;
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
       $ticket->branch_id = $request->branch ?? "";
       $ticket->category_id = $request->category ?? "";
       $ticket->help_topic_id = $request->help_topic ?? "";
       $ticket->title = $request->title;
       $ticket->email = $request->email;
       $ticket->status = 'pending';
       $ticket->priority = $request->priority;
       $ticket->message = htmlspecialchars($request->value);
       $ticket->ticket_number = mt_rand(10000,99999);
       $ticket->save();
        if($request->hasFile('ticket_files')) {
            $image = $request->file('ticket_files');
            $filename = time().'.'.$image->getClientOriginalExtension();

            $image->move(public_path('files'),$filename);

            $path = url('').'/files/' . $filename;

            $newFile = new Image();
            $newFile->ticket_id = $ticket->id;
            $newFile->url = $path;
            $newFile->save();

        }

       Mail::to($request->email)->send(new ReceivedTicketMail($ticket));
       Mail::to("tech@primeinsuranceghana.com")->send(new AutoRespond($ticket));

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
            'category' => $ticket->category?->name,
            'priority' => $ticket->priority,
            'help_topic' => $ticket->help_topic->name,
            'status' => $ticket->status,
            'file' => $ticket->images->first()?->url
        ]);
    }

    public function delete(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['success' => true]);
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

    public function reply_ticket(Request $request)
    {
//        $this->validate($request,[
//            'reply' => ['required']
//        ]);

        $ticket = Ticket::where('ticket_number',$request->ticket_number)->first();
        $ticket->status = $request->status;
        $ticket->priority = $request->priority;
        $ticket->user_id = $request->user_id;
        $ticket->update();

        $data = $request->all();

        Mail::to($request->email)->send(new ReplyTicket($data));


    }
    public function get_branches_report(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = '';

        if($request->model === "" || $request->model == 'branch') {
            $model = '\App\Models\Branch';
        }elseif ($request->model == 'category') {
             $model = '\App\Models\Category';
        }elseif ($request->model == 'help_topic' ) {
            $model = '\App\Models\HelpTopic';
        }else {
            $model = '\App\Models\Branch';
        }
        $s = Carbon::createFromFormat('Y-m-d', $request->s ? $request->s : Carbon::now()->format('Y-m-01'))->startOfDay();
        $e = Carbon::createFromFormat('Y-m-d', $request->e ? $request->e : Carbon::now()->format('Y-m-t'))->endOfDay();

        $branchesCount = $model::query()->orderBy('created_at','desc')->get()->map(function ($t) use ($s,$e) {
           return $t->tickets->whereBetween('created_at',[$s , $e])->count();
        });
        $branch = Branch::query()->count();
        $ticket = Ticket::query()->count();
        $help_topics = HelpTopic::query()->count();
        $category = Category::query()->count();

        $branchNames = $model::orderBy('created_at','desc')->pluck('name');

        return response()->json([
            'count' => $branchesCount,
            'branch_names' => $branchNames,
            'branch' => $branch,
            'category' => $category,
            'ticket' => $ticket,
            'help_topic' => $help_topics,
            'e' => $e,
            's' => $s
        ]);
    }

    public function report(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = '';

        if($request->model === "" || $request->model == 'branch') {
            $model = '\App\Models\Branch';
        }elseif ($request->model == 'category') {
            $model = '\App\Models\Category';
        }elseif ($request->model == 'help_topic' ) {
            $model = '\App\Models\HelpTopic';
        }else {
            $model = '\App\Models\Branch';
        }
        $s = Carbon::createFromFormat('Y-m-d', $request->s ? $request->s : Carbon::now()->format('Y-m-01'))->startOfDay();
        $e = Carbon::createFromFormat('Y-m-d', $request->e ? $request->e : Carbon::now()->format('Y-m-t'))->endOfDay();
        $pending = Ticket::whereBetween('created_at',[$s,$e])->where('status','pending')->count();
        $resolved = Ticket::whereBetween('created_at',[$s,$e])->where('status','resolved')->count();
        $rejected = Ticket::whereBetween('created_at',[$s,$e])->where('status','rejected')->count();
        $total = Ticket::whereBetween('created_at',[$s,$e])->count();
        $models = $model::query()->orderBy('created_at','desc')->get()->map(function ($m) use ($s,$e) {
            return [
                'mode' => "span",
                'label' => $m->name,
                'html' => false,
                'children' => $m->tickets->whereBetween('created_at',[$s , $e])->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'title' => $t->title,
                        'email' => $t->email,
                        'ticket_number' => $t->ticket_number,
                        'branch' => $t->branch->name,
                        'status' => $t->status,
                        'priority' => $t->priority,
                        'resolved_by' => $t->user?->name
                    ];
                })->values()
            ];
        });
        return response()->json(['models' => $models,'pending' => $pending,'resolved' => $resolved,'rejected' => $rejected,'total' => $total]);
    }
    public function print(Request $request)
    {
        $s = Carbon::createFromFormat('Y-m-d', $request->s ? $request->s : Carbon::now()->format('Y-m-01'))->startOfDay();
        $e = Carbon::createFromFormat('Y-m-d', $request->e ? $request->e : Carbon::now()->format('Y-m-t'))->endOfDay();

        $tickets = Ticket::whereBetween('created_at',[$s,$e])->get()->sortBy(function ($q) {
            return $q->branch->id;
        })->map(function ($t) {
            return [
                'id' => $t->id,
                'ticket_number' => $t->ticket_number,
                'branch' => $t->branch->name,
                'email' => $t->email,
                'title' => $t->title,
                'priority' => $t->priority,
                'status' => $t->status,
                'resolved_by' => $t->user?->name
            ];
        })->values();
        return response()->json($tickets);
    }
}
