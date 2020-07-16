<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function personalToken()
    {
        return view('token.personal_token');
    }
    public function clientsToken()
    {
        return view('token.clients_token');
    }
    public function authorizedClientToken()
    {
        return view('token.authorize_clients_token');
    }
}
