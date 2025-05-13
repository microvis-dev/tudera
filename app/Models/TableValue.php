<?php

namespace App\Models;

use App\Enums\RolesEnum;
use App\Services\MessagingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use App\Services\PermissionService;

class TableValue extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [ 
        'column_id',
        'order',
        'value'
    ];

    public static $bypassProtectionCheck = false;

    private static function checkProtected(self $value, $roles)
    {
        if (!self::$bypassProtectionCheck) {
            if (!PermissionService::userHasWorkspacePerm(auth()->user(), $value->column->workspace_table->workspace, $roles)) {
                return true;
            }
        }
        return false;
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (static::checkProtected($model, [RolesEnum::ADMIN, RolesEnum::EDITOR])) throw new \Exception("Cannot add value to this table.");
        });

        self::updating(function ($model) {
            if (static::checkProtected($model, [RolesEnum::ADMIN, RolesEnum::EDITOR])) throw new \Exception("Cannot update value at this table.");
        });

        self::deleting(function ($model) {
            if (static::checkProtected($model, [RolesEnum::ADMIN, RolesEnum::EDITOR])) throw new \Exception("Cannot delete value from this table.");
        });

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
