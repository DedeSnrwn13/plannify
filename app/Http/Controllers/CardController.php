<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Response;
use App\Enums\CardStatus;
use App\Models\Workspace;
use App\Enums\CardPriority;
use Illuminate\Http\Request;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardSingleResource;
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

        $card->members()->create([
            'user_id' => $request->user()->id,
            'role' => $card->user_id == $request->user()->id  ? 'Owner' : 'Member'
        ]);

        flashMessage('Card information saved successfully');

        return to_route('cards.edit', [$workspace, $card]);
    }

    public function show(Workspace $workspace, Card $card): Response
    {
        return inertia('Cards/Show', [
            'card' => fn() => new CardSingleResource($card->load(['members', 'user', 'tasks', 'attachments'])),
            'page_settings' => [
                'title' => 'Detail Card',
                'subtitle' => 'You can see card information',
            ]
        ]);
    }

    public function edit(Workspace $workspace, Card $card): Response
    {
        return inertia('Cards/Edit', [
            'card' => fn() => new CardSingleResource($card->load(['members', 'user', 'tasks', 'attachments'])),
            'page_settings' => [
                'title' => 'Edit Card',
                'subtitle' => 'Fill out this form to edit card',
                'method' => 'PUT',
                'action' => route('cards.update', [$workspace, $card])
            ],
            'statuses' => CardStatus::options(),
            'priorities' => CardPriority::options(),
            'workspace' => fn() => $workspace->only('slug'),
        ]);
    }

    public function update(Workspace $workspace, Card $card, CardRequest $request): RedirectResponse
    {
        $last_status = $card->status->value;

        $card->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => $status = $request->status,
            'priority' => $request->priority,
            'order' => $this->ordering($workspace, $status),
        ]);

        $this->adjustOrdering($workspace, $last_status);

        flashMessage('Successfully updated card information');

        return back();
    }

    public function destroy(Workspace $workspace, Card $card): RedirectResponse
    {
        $last_status = $card->status->value;

        $card->delete();

        $this->adjustOrdering($workspace, $last_status);

        flashMessage('The cars has been successfully deleted');

        return to_route('workspaces.show', $workspace);
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

    public function adjustOrdering(Workspace $workspace, string $status)
    {
        $order = 1;

        return Card::where('workspace_id', $workspace->id)
            ->where('status', $status)
            ->orderBy('order')
            ->get()
            ->each(function ($card) use (&$order) {
                $card->order = $order;
                $card->save();
                $order++;
            });
    }
}