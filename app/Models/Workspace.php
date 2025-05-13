<?php

namespace App\Models;

use App\Enums\RolesEnum;
use App\Services\WorkspaceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            if ($users_to_workspace->save()) {
                if (WorkspaceService::change($user, $model)) {
                    $user->assignRole(RolesEnum::ADMIN);
                }
            }
        });
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

    /**
     * Profilkép feltöltése
     *
     * @param UploadedFile $image
     * @return void
     */
    public function uploadProfileImage(UploadedFile $image): void
    {
        if ($this->profile_image) {
            Storage::disk('s3')->delete($this->profile_image);
        }

        $filename = \Illuminate\Support\Str::uuid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('workspace_images', $filename, 's3');
        $this->profile_image = $path;
        $this->save();
    }

    /**
     * Profilkép URL-jének lekérése
     *
     * @param string|null $value
     * @return string|null
     */
    public function getProfileImageAttribute(?string $value): ?string
    {
        if (!$value) {
            return app('url')->asset("assets/user.png", false);
        }

        return Storage::disk("s3")->temporaryUrl($value, now()->addMinutes(5));
    }
}
