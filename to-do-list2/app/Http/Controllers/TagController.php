<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagStoreRequest;
use App\Models\Tag;
use App\Models\TagUser;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(TagStoreRequest $request)
    {
        $request = $request->validated();
        $request['user_id'] = auth()->user()->id;
        Tag::create($request);
        return redirect()->route('home');
    }
}
