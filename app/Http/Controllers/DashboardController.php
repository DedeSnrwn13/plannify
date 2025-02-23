<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return inertia('Dashboard');
    }
}