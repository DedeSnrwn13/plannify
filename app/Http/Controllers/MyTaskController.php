<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Response;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Resources\MyTaskResource;

class MyTaskController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $tasks = Member::query()
            ->where('members.user_id', $request->user()->id)
            ->whereHasMorph('memberable', Card::class)
            ->get();

        return inertia('Tasks/Index', [
            'tasks' => fn() => MyTaskResource::collection($tasks),
            'page_settings' => [
                'title' => 'Tasks',
                'subtitle' => 'A list of all the task in your platform',
            ],
        ]);
    }
}
