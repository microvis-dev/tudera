<?php
namespace App\Models;

use App\Services\MessagingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'profile_image',
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function (User $user) {
            MessagingService::sendToUser(
                $user,
                [MessagingService::TYPE_NOTIFICATION, MessagingService::TYPE_EMAIL],
                'Welcome to Tudera!<br>We are glad to have you here. If you have any questions, feel free to reach out to us.',
                'Welcome to Tudera',
            );
        });
    }

    public function workspaces(): BelongsToMany {
        return $this->belongsToMany(Workspace::class, 'users_to_workspace', 'user_id', 'workspace_id');
    }

    public function tables() {
        return $this->hasMany(WorkspaceTable::class)->whereIn('workspace_id', $this->workspaces()->pluck('id'));
    }

    public function todos(): HasMany {
        return $this->hasMany(TodoList::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
        $path = $image->storeAs('profile_images', $filename, 's3');
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
