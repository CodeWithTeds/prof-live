<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Project extends Model
{
    use SoftDeletes;

    protected $fillable  = [
        'name',
        'description',
        'status',
        'priority',
        'start_date',
        'end_date',
        'budget',
        'project_id',

    ];


    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'end_date' => 'datetime',
            'budget' => 'decimal:2',
            'deleted_at' => 'datatime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
