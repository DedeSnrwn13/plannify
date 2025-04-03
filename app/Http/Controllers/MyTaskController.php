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
            ->when($request->search, function ($query, $value) {
                return $query->whereHasMorph('memberable', Card::class, function ($subQuery) use ($value) {
                    $subQuery->where('title', 'REGEXP', $value);
                });
            })
            ->paginate(10);

        return inertia('Tasks/Index', [
            'tasks' => fn() => MyTaskResource::collection($tasks)->additional([
                'meta' => [
                    'has_pages' => $tasks->hasPages(),
                ],
            ]),
            'page_settings' => [
                'title' => 'Tasks',
                'subtitle' => 'A list of all the task in your platform',
            ],
            'state' => [
                'page' => $request->page ?? 1,
                'search' => $request->search ?? '',
            ],
        ]);
    }
}
