<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    public function __construct(
        private array $params,
    )
    {
    }

    public function applyFilters(Builder $builder)
    {
//        dd($this->getCallbacks());
        foreach ($this->getCallbacks() as $key => $callbacks){
            if(isset($this->params[$key])){
                $this->$callbacks($builder, $this->params[$key]);
            }
        }
    }
    abstract public function getCallbacks():array;
}
