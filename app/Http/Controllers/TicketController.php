<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
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
    }
}
