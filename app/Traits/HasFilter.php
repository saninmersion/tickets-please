<?php

namespace App\Traits;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        $filters->apply($builder);
    }
}
