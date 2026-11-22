<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAnalytics;

class DashboardController extends Controller
{
    public function index(AdminAnalytics $analytics)
    {
        return view('admin.dashboard', ['a' => $analytics->build()]);
    }
}
