<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TaskFilter extends AbstractFilter
{
    const CATEGORY = 'category';
    const TAG = 'tag';

    public function getCallbacks(): array
    {
        return [
            self::CATEGORY => 'category',
            self::TAG => 'tag',
        ];
    }

    public function category(Builder $builder, $value)
    {
        $builder->whereHas('category', function (Builder $query) use ($value) {
            $query->where('category', $value);
        });
    }

    public function tag(Builder $builder, $value)
    {
        $builder->whereHas('tags', function (Builder $query) use ($value) {
            $query->where('tag', $value);
        });
    }
}
