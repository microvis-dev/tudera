<?php

namespace App\Models;

use App\WorkspaceColumnTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class WorkspaceColumn extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'table_id',
        'type',
        'name',
        'order'
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
        'type' => WorkspaceColumnTypeEnum::class,
        'name' => 'string',
        'order' => 'integer'
    ];
}