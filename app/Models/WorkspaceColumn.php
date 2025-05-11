<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public static $bypassProtectionCheck = false;

    public function workspace_table(): BelongsTo {
        return $this->belongsTo(WorkspaceTable::class, 'table_id');
    }

    public function values() {
        return $this->hasMany(TableValue::class, 'column_id')->orderBy('order');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'table_id' => 'string', // uuid
        'type' => 'string', // WorkspaceColumnTypeEnum::class
        'name' => 'string',
        'order' => 'integer'
    ];

    private static function checkProtected(WorkspaceColumn $column)
    {
        return $column->workspace_table()->first()->protected && !self::$bypassProtectionCheck;
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (static::checkProtected($model)) throw new \Exception("Cannot create protected columns.");
        });

        self::updating(function ($model) {
            if (static::checkProtected($model)) throw new \Exception("Cannot update protected columns.");
        });

        self::deleting(function ($model) {
            if (static::checkProtected($model)) throw new \Exception("Cannot delete protected columns.");
        });
    }
}
