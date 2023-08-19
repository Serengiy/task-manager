<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sticker\StoreRequest;
use App\Http\Requests\Sticker\UpdateRequest;
use App\Models\Category;
use App\Models\Sticker;
use App\Models\Tag;
use Illuminate\Http\Request;

class StickerController extends Controller
{
    public function index()
    {
        $stickers = Sticker::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->user()->id)->get();
        $lists = Category::where('user_id', auth()->user()->id)->with('tasks')->get();
        return inertia('StickerWall', compact('tags', 'lists', 'stickers'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        Sticker::create($data);
        return redirect()->back();
    }

    public function update(UpdateRequest $request,Sticker $sticker)
    {
        $sticker->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Sticker $sticker)
    {
        $sticker->delete();
        return redirect()->back();
    }
}
