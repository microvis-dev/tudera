<?php

namespace App\Enums;

enum RolesEnum: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case USER = 'user';

    public function labels() : array
    {
        return [
            self::OWNER->value => 'Owner',
            self::ADMIN->value => 'Admin',
            self::USER->value => 'User',
        ];
    }
}
