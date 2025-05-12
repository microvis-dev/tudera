<?php

namespace App\Models;

use App\Services\MessagingService;
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
        'column_id',
        'order',
        'value'
    ];

    protected static function boot()
    {
        parent::boot();

        self::updated(function ($tableValue) {
            if ($tableValue->column->type == 'status') {
                $oldValue = $tableValue->getOriginal('value');
                $newValue = $tableValue->value;
                $column = $tableValue->column;
                $table = $tableValue->column->workspace_table;
                $workspace = $tableValue->column->workspace_table->workspace;
                MessagingService::sendToWorkspace(
                    $workspace,
                    [MessagingService::TYPE_NOTIFICATION],
                    'A status has been changed in '.$table->name.' '.$column->name.' from '.$oldValue.' to '.$newValue,
                    'Status has been changed'
                );
            }
        });
    }

    public function column(): BelongsTo {
        return $this->belongsTo(WorkspaceColumn::class, 'column_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'column_id' => 'integer',
        'order' => 'integer',
        'value' => 'string',
    ];
}
