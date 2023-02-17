<?php

namespace App\Http\Controllers;

use App\Models\HelpTopic;
use Illuminate\Http\Request;

class HelpTopicController extends Controller
{
    public function index()
    {
        return response()->json(HelpTopic::all()->map(function ($topic) {
            return [
                'id' => $topic->id,
                'name' => $topic->name,
                'show_category' => $topic->show_category,
                'ticket_raised' => $topic->tickets->count()
            ];
        }));
    }
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:help_topics']
        ]);
        $topic = new HelpTopic();
        $topic->name = $request->name;
        $topic->show_category = $request->show_category;

        $topic->save();

        return response()->json($topic);
    }
    public function show(HelpTopic $helpTopic): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'id' => $helpTopic->id,
            'name' => $helpTopic->name,
            'show_category' => $helpTopic->show_category,
            'ticket_raised' => $helpTopic->tickets->count()
        ]);
    }
    public function update(Request $request,HelpTopic $helpTopic)
    {
        $this->validate($request,[
            'name' => ['required','unique:help_topics,name,'.$helpTopic->id]
        ]);
        $helpTopic->name = $request->name;
        $helpTopic->show_category = $request->show_category;

        $helpTopic->update();
    }
    public function delete(HelpTopic $helpTopic): \Illuminate\Http\JsonResponse
    {
        $helpTopic->delete();
        return response()->json(['success' => true]);
    }
}
