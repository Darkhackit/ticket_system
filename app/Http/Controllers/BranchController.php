<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Branch::all()->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'ticket_raised' => $branch->tickets->count()
            ];
        }));
    }
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:branches']
        ]);
        $branch = new Branch();
        $branch->name = $request->name;

        $branch->save();

        return response()->json($branch);
    }
    public function show(Branch $branch): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'id' => $branch->id,
            'name' => $branch->name,
            'ticket_raised' => 0
        ]);
    }
    public function update(Request $request,Branch $branch)
    {
        $this->validate($request,[
            'name' => ['required','unique:branches,name,'.$branch->id]
        ]);
        $branch->name = $request->name;

        $branch->update();
    }
    public function delete(Branch $branch): \Illuminate\Http\JsonResponse
    {
        $branch->delete();
        return response()->json(['success' => true]);
    }
}
