<?php

namespace App\Models;

use App\Enums\WorkspaceVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'cover',
        'logo',
        'visibility'
    ];

    protected function casts(): array
    {
        return [
            'visibility' => WorkspaceVisibility::class
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(related: Card::class);
    }

    public function members(): MorphMany
    {
        return $this->morphMany(related: Member::class, 'memberable');
    }
}
