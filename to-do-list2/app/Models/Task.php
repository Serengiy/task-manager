<?php

namespace App\Models;

use App\Models\Traits\HasFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory; use HasFilter;
    protected $table = 'tasks';
    protected $casts = [
        'due_date' => 'date:Y-m-d'
    ];
//    protected $with = ['category'];
    protected $guarded = false;

    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_tasks', 'task_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_tasks', 'task_id', 'tag_id');
    }
}
