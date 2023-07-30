<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Category;
use App\Models\CategoryTask;
use App\Models\Tag;
use App\Models\TagTask;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id',auth()->user()->id)->with('category')->with('tags')->get();
        $tags = Tag::where('user_id', auth()->user()->id)->get();
        $lists = Category::where('user_id', auth()->user()->id)->with('tasks')->get();
        return inertia('Welcome', compact( 'lists', 'tags', 'tasks'));
    }

    public function store(StoreRequest $request)
    {;
        $request = $request->validated();
        $request['user_id'] = auth()->user()->id;
        $list = $request['list'];
        $tags = $request['tags'];

        unset($request['list']);
        unset($request['tags']);

        $task = Task::create($request);

        $this->storePivotTags($tags, $task);
        $this->storePivotCategory($list, $task);

        return redirect()->route('home');
    }

    public function update(UpdateRequest $request, Task $task)
    {
        dd($task);
    }


    private function storePivotTags($tags, $task)
    {
        $tags = Tag::whereIn('tag', $tags)->where('user_id', auth()->user()->id)->get();
        foreach ($tags as $tag){
            TagTask::create([
                'tag_id' => $tag->id,
                'task_id' => $task->id
            ]);
        }
    }

    private function storePivotCategory($list, $task)
    {
        $list = Category::where('category', $list)->where('user_id', auth()->user()->id)->first();

        CategoryTask::create([
            'category_id' => $list->id,
            'task_id' => $task->id
        ]);
    }
}
