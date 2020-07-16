<?php

namespace App\Http\Controllers;

use App\Traits\apiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use apiResponser;

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
