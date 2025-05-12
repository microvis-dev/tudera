<?php

namespace App\Models;

use App\Services\MessagingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class WorkspaceTable extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'workspace_id',
        'name',
        'protected',
    ];

    public static $bypassProtectionCheck = false;
    private static $copy = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    private static function checkProtected(self $table)
    {
        return $table->protected && !self::$bypassProtectionCheck;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            WorkspaceColumn::$bypassProtectionCheck = true;
            if (!static::$copy) {
                foreach ($model->columns() as $column) {
                    $column->order++;
                    $column->save();
                }
                $model->columns()->create([
                    'table_id' => $model->id,
                    'type' => 'string',
                    'name' => strip_tags($model->name),
                    'order' => 1,
                ]);
            }
        });

        self::creating(function ($model) {
            if (static::checkProtected($model)) throw new \Exception("Cannot create protected tables.");
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });

        self::updating(function ($model) {
            if (static::checkProtected($model)) throw new \Exception("Cannot update protected tables.");
        });

        self::deleting(function ($model) {
            if (static::checkProtected($model)) throw new \Exception("Cannot delete protected tables.");
        });
    }

    public function workspace(): BelongsTo {
        return $this->belongsTo(Workspace::class);
    }

    public function columns(): HasMany {
        return $this->hasMany(WorkspaceColumn::class, 'table_id');
    }

    /**
     * Copy the table to another workspace with all its relations
     *
     * @param Workspace $toWorkspace The target workspace
     * @return WorkspaceTable|null The new table or null if copying failed
     */
    public function copy(Workspace $toWorkspace): ?WorkspaceTable
    {
        static::$copy = true;
        $this->load('columns.values');
        static::$bypassProtectionCheck = true;

        $newTable = $this->replicate();
        $newTable->workspace()->associate($toWorkspace);
        $newTable->save();

        foreach ($this->columns as $column) {
            WorkspaceColumn::$bypassProtectionCheck = true;
            $newColumn = $column->replicate();
            $newColumn->table_id = $newTable->id;
            $newColumn->save();

            foreach ($column->values as $field) {
                $newField = $field->replicate();
                $newField->column_id = $newColumn->id;
                $newField->save();
            }
        }

        return $newTable;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'workspace_id' => 'integer',
        'name' => 'string',
        'protected' => 'boolean',
    ];
}
