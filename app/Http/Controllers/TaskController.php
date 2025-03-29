<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function store(Card $card, Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255']
        ]);

        $request->user()->tasks()->create([
            'card_id' => $card->id,
            'title' => $request->title,
        ]);

        flashMessage('Task was saved successfully');
        return back();
    }
}