<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class WorkspaceRow extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'table_id',
        'order',
        'name',
    ];

    public function workspace_table(): BelongsTo {
        return $this->belongsTo(WorkspaceTable::class, 'table_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'table_id' => 'string', // uuid
        'order' => 'integer',
        'name' => 'string',
    ];
}