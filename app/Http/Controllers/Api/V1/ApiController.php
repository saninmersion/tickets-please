<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

    public function include(string $relationship): bool
    {
        $params = request()->get('include');

        if ( !isset($params) ) {
            return false;
        }

        $includeValues = explode(',', Str::lower($params));

        return in_array(Str::lower($relationship), $includeValues);
    }

    public function isAble($ability, $targetModel)
    {
        return $this->authorize($ability, [$targetModel, $this->policyClass]);
    }
}
