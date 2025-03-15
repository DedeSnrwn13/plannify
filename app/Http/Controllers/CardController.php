<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Response;
use App\Enums\CardStatus;
use App\Models\Workspace;
use App\Enums\CardPriority;
use Illuminate\Http\Request;
use App\Http\Requests\CardRequest;
use Illuminate\Http\RedirectResponse;

class CardController extends Controller
{
    public function create(Workspace $workspace): Response
    {
        return inertia('Cards/Create', [
            'page_settings' => [
                'title' => 'Create Card',
                'subtitle' => 'Fill out this form to add a new card',
                'method' => 'POST',
                'action' => route('cards.store', $workspace),
            ],
            'status' => request()->status ?? 'To Do',
            'statuses' => CardStatus::options(),
            'priority' => request()->priority ?? CardPriority::UNKNOWN->value,
            'priorities' => CardPriority::options(),
            'workspace' => fn() => $workspace->only('slug')
        ]);
    }

    public function store(Workspace $workspace, CardRequest $request): RedirectResponse
    {
        $card = $request->user()->cards()->create([
            'workspace_id' => $workspace->id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => $status = $request->status,
            'order' => $this->ordering($workspace, $status),
            'priority' => $request->priority,
        ]);

        flashMessage('Card information saved successfully');

        return to_route('workspaces.show', [$workspace]);
    }

    public function ordering(Workspace $workspace, string $status): int
    {
        $last_card = Card::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', $status)
            ->orderByDesc('order')
            ->first();

        if ($last_card) return 1;

        return $last_card?->order + 1;
    }
}
