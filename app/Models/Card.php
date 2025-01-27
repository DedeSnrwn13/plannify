<?php

namespace App\Models;

use App\Enums\CardStatus;
use App\Enums\CardPriority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workspace_id',
        'title',
        'description',
        'deadline',
        'order',
        'status',
        'priority'
    ];

    protected function casts(): array
    {
        return [
            'status' => CardStatus::class,
            'priority' => CardPriority::class
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(related: Workspace::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(related: Attachment::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(related: Task::class);
    }

    public function members(): MorphMany
    {
        return $this->morphMany(related: Member::class, 'memberable');
    }
}
