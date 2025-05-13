<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case VIEWER = 'viewer';

    public function labels() : array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::EDITOR->value => 'Editor',
            self::VIEWER->value => 'Viewer',
        ];
    }
}
