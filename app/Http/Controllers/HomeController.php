<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.item')->latest()->get();
        return view('home', compact('orders'));
    }
    public function orderHistory()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.item')->latest()->get();
        return response()->json($orders);
    }
}
