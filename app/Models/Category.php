<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
//    protected $with = ['tasks'];
    protected $guarded = false;

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'category_tasks', 'category_id', 'task_id');
    }
}
