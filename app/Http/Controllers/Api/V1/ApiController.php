<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function include(string $relationship): bool
    {
        $params = request()->get('include');

        if ( !isset($params) ) {
            return false;
        }

        $includeValues = explode(',', Str::lower($params));

        return in_array(Str::lower($relationship), $includeValues);
    }
}
