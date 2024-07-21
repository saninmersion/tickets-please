<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Builder $builder;
    protected Request $request;
    protected array   $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function filter($arr): Builder
    {
        foreach ($arr as $key => $value) {
            if ( method_exists($this, $key) ) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function sort($value): Builder
    {
        $sortAttributes = explode(',', $value);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            if ( Str::position($sortAttribute, '-' === 0) ) {
                $direction     = 'desc';
                $sortAttribute = Str::substr($sortAttribute, 1);
            }

            if ( !in_array($sortAttribute, $this->sortable) || array_key_exists($sortAttribute, $this->sortable)) {
                continue;
            }

            $columnName = $this->sortable[$sortAttribute] ?? $sortAttribute;

            $this->builder->orderBy($columnName, $direction);
        }

        return $this->builder;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if ( method_exists($this, $key) ) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }
}
