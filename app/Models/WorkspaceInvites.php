<?php

namespace App\Models;

use App\Enums\RolesEnum;
use App\Services\WorkspaceService;
use Illuminate\Database\Eloquent\Model;
use Str;

class WorkspaceInvites extends Model
{
    protected $table = 'workspace_invites';
    protected $primaryKey = 'invite_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->invite_id = (string) Str::uuid();
        });

        static::updated(function ($model) {
            if ($model->isDirty('used') && $model->used) {
                if ($workspace = $model->workspace()->first()) {
                    $user = auth()->user();
                    if ($user) {
                        WorkspaceService::assign($user, $workspace);
                    }
                }
            }
        });
    }

    protected $fillable = [
        'invite_id',
        'workspace_id',
        'used',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
