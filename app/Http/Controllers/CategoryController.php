<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(CategoryStoreRequest $request)
    {
        $request = $request->validated();
        $request['user_id'] = auth()->user()->id;
        Category::create($request);
        $lists = Category::where('user_id', auth()->user()->id)->with('tasks')->get();
        return redirect()->route('home', compact('lists'));
    }
}
