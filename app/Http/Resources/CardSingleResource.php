<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CardSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'workspace_id' => $this->workspace_id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => [
                'format' => $this->deadline ?  Carbon::createFromFormat('Y-m-d', $this->deadline)->format('d M Y') : null,
                'unformatted' => $this->deadline,
            ],
            'status' => $this->status->value,
            'priority' => $this->priority,
            'created_at' => $this->created_at->format('d M Y'),
        ];
    }
}