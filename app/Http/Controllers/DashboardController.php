<?php

namespace App\Http\Controllers;

use App\Enums\CardStatus;
use App\Models\Card;
use App\Models\User;
use Inertia\Response;
use App\Models\Member;
use App\Models\Workspace;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return inertia('Dashboard', [
            'page_settings' => [
                'title' => 'Dashboard',
                'subtitle' => 'You can see a summary of the information here',
            ],
            'count' => [
                'users' => fn() => User::count(),
                'workspaces' => fn() => Member::query()
                    ->where('members.user_id', request()->user()->id)
                    ->whereHasMorph('memberable', Workspace::class)
                    ->count(),
                'tasks' => fn() => Member::query()
                    ->where('members.user_id', request()->user()->id)
                    ->whereHasMorph('memberable', Card::class)
                    ->count(),
                'dones' => fn() => Member::query()
                    ->where('members.user_id', request()->user()->id)
                    ->whereHasMorph(
                        'memberable',
                        Card::class,
                        fn($query) => $query->where('status', CardStatus::DONE->value)
                    )
                    ->count(),
            ]
        ]);
    }
}
