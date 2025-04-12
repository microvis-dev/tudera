<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UsersToWorkspace;

class Role extends Model
{
    protected $fillable = ['name'];

    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }

    public function users() {
        return $this->hasMany(UsersToWorkspace::class);
    }
}
