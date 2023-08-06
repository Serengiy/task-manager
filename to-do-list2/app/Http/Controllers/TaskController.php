<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Category;
use App\Models\CategoryTask;
use App\Models\Tag;
use App\Models\TagTask;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {;
        $tasks = Task::where('user_id',auth()->user()->id)->with('category')->with('tags')->latest()->get();
        $tags = Tag::where('user_id', auth()->user()->id)->get();
        $lists = Category::where('user_id', auth()->user()->id)->with('tasks')->get();
        return inertia('Home', compact( 'lists', 'tags', 'tasks'));
    }

    public function store(StoreRequest $request)
    {
        $request = $request->validated();
        $request['user_id'] = auth()->user()->id;
        $list = $request['list'];
        $tags = $request['tags'];

        unset($request['list']);
        unset($request['tags']);


        $task = Task::create($request);
        $this->storePivotTags($tags, $task);
        $this->storePivotCategory($list, $task);

        return back();

    }

    public function update(UpdateRequest $request, Task $task)
    {
        $data = $request->validated();
        $this->updatePivotTags($data['tags'], $task);
        $this->updatePivotCategory($data['list'], $task);
        unset($data['tags'], $data['list']);
        $task->update($data);

        return redirect()->back();
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

    private function updatePivotTags($tags, $task)
    {
        $task->tags()->detach($task['tags']);
        $tags = Tag::where('user_id', auth()->id())->whereIn('tag', $tags)->get();
        $task->tags()->attach($tags);
    }

    private function updatePivotCategory($list, $task)
    {
        $task->category()->detach($task['category']);
        $list = Category::where('user_id', auth()->id())->where('category', $list)->get();
//        dd($list);
        $task->category()->attach($list);
    }
}
