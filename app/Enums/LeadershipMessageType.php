<?php

namespace App\Enums;

enum LeadershipMessageType: string
{
    case Chairman = 'chairman';
    case VcAcademic = 'vc_academic';
    case VcAdmin = 'vc_admin';
    case Department = 'department';
    case Faculty = 'faculty';

    public function label(): string
    {
        return match ($this) {
            self::Chairman => 'Chairman Message',
            self::VcAcademic => 'VC Academic Message',
            self::VcAdmin => 'VC Admin Message',
            self::Department => 'Department Message',
            self::Faculty => 'Faculty Message',
        };
    }
}
