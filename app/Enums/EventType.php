<?php

namespace App\Enums;

enum EventType: string
{
    case Conference = 'conference';
    case Workshop = 'workshop';
    case Seminar = 'seminar';
    case Webinar = 'webinar';
    case Symposium = 'symposium';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Conference => 'Conference',
            self::Workshop => 'Workshop',
            self::Seminar => 'Seminar',
            self::Webinar => 'Webinar',
            self::Symposium => 'Symposium',
            self::Other => 'Other',
        };
    }
}
