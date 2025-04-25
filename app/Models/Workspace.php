<?php

namespace App\Models;

use App\Enums\RolesEnum;
use App\Services\WorkspaceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Workspace extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $user = Auth::user();
            $users_to_workspace = new UsersToWorkspace();

            $users_to_workspace->workspace_id = $model->id;
            $users_to_workspace->user_id = $user->id;

            if ($users_to_workspace->save() && self::makeRoles($model)) {
                if (WorkspaceService::change($user, $model)) {
                    $user->assignRole(RolesEnum::OWNER);
                }
            }
        });
    }

    private static function makeRoles(Workspace $workspace) : bool
    {
        foreach (RolesEnum::cases() as $role) {
            Role::create([
                'name' => $role->value,
                'team_id' => $workspace->id,
            ]);
        }
        return true;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_to_workspace', 'workspace_id', 'user_id');
    }

    public function tables(): HasMany {
        return $this->hasMany(WorkspaceTable::class, 'workspace_id', 'id');
    }

    public function calendar_events() : HasMany {
        return $this->hasMany(Calendar::class, 'workspace_id', 'id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
    ];
}
