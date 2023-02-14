<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'ticket_raised' => 0
            ];
        }));
    }
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required','unique:categories']
        ]);
        $category = new Category();
        $category->name = $request->name;

        $category->save();

        return response()->json($category);
    }
    public function show(Category $category): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'ticket_raised' => $category->tickets->count()
        ]);
    }
    public function update(Request $request,Category $category)
    {
        $this->validate($request,[
            'name' => ['required','unique:categories,name,'.$category->id]
        ]);
        $category->name = $request->name;

        $category->update();
    }
    public function delete(Category $category): \Illuminate\Http\JsonResponse
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}
