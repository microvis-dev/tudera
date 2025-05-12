<?php

namespace App\Models;

use App\Services\MessagingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class UsersToWorkspace extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'users_to_workspace';

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            MessagingService::sendToWorkspace($model->workspace, [
                MessagingService::TYPE_NOTIFICATION,
                MessagingService::TYPE_EMAIL
            ], 'Dear User!<br>'.$model->user->name.' just joined '.$model->workspace->name.'.', 'User joined workspace');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'workspace_id',
        'role_id'
    ];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function workspace() {
        return $this->belongsTo(Workspace::class);
    }


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'workspace_id' => 'integer'
    ];
}
