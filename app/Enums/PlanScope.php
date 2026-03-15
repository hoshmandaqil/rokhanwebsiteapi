<?php

namespace App\Enums;

enum PlanScope: string
{
    case General = 'general';
    case Faculty = 'faculty';
    case Department = 'department';
    case Research = 'research';

    public function label(): string
    {
        return match ($this) {
            self::General => 'General',
            self::Faculty => 'Faculty',
            self::Department => 'Department',
            self::Research => 'Research',
        };
    }
}
