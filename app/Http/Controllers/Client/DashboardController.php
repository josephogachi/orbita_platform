<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the logged-in user's orders, most recent first
        $orders = auth()->user()->orders()->latest()->get();

        return view('dashboard', compact('orders'));
    }
}