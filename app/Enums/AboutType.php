<?php

namespace App\Enums;

enum AboutType: string
{
    case University = 'university';
    case History = 'history';
    case Program = 'program';
    case Department = 'department';

    public function label(): string
    {
        return match ($this) {
            self::University => 'University',
            self::History => 'History',
            self::Program => 'Program',
            self::Department => 'Department',
        };
    }
}
