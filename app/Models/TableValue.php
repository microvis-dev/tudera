<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class TableValue extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [ // !id
        'row_id',
        'column_id',
        'value'
    ];

    public function column(): BelongsTo {
        return $this->belongsTo(WorkspaceColumn::class, 'column_id');
    }

    public function row(): BelongsTo {
        return $this->belongsTo(WorkspaceRow::class, 'row_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'row_id' => 'integer',
        'column_id' => 'integer',
        'value' => 'string|null', 
    ];

}
