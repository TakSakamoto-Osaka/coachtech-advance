<?php
namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

class GuestController extends Controller
{

    public function index()
    {
        return view('index');
    }
}
