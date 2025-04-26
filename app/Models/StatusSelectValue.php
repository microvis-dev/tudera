<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusSelectValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'column_id',
        'value',
    ];

    public function column()
    {
        return $this->belongsTo(WorkspaceColumn::class, 'column_id');
    }
}
