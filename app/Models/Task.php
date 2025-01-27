<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'parent_id',
        'title',
        'is_completed'
    ];

    protected $with = ['card', 'children', 'user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(related: Card::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(related: Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(related: Task::class, 'parent_id');
    }
}
