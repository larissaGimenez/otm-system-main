<?php

namespace App\Enums\User;

enum UserRole: string
{
    
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';
    case FIELD = 'field';


    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gerente',
            self::STAFF => 'Equipe Interna',
            self::FIELD => 'Equipe de Campo',
        };
    }
}