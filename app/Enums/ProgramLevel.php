<?php

namespace App\Enums;

enum ProgramLevel: string
{
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Del = 'del';
    case Dit = 'dit';

    public function label(): string
    {
        return match ($this) {
            self::Bachelor => 'Bachelor',
            self::Master => 'Master',
            self::Del => 'DEL',
            self::Dit => 'DIT',
        };
    }

    /** Frontend path segment (matches existing Nuxt routes). */
    public function pathSegment(): string
    {
        return match ($this) {
            self::Bachelor => 'bachlore',
            self::Master => 'master',
            self::Del => 'del',
            self::Dit => 'dit',
        };
    }
}
