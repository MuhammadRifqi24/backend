<?php

namespace App\Http\Controllers\API;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;

class BaseController extends Controller
{
    use ApiResponser;

    protected function guard()
    {
        return Auth::guard();
    }
}
