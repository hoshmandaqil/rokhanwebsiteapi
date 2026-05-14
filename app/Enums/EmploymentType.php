<?php

namespace App\Enums;

enum EmploymentType: string
{
    case FullTime = 'Full-time';
    case PartTime = 'Part-time';
    case Contract = 'Contract';
    case Temporary = 'Temporary';
    case Internship = 'Internship';
    case Volunteer = 'Volunteer';

    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'Full-time',
            self::PartTime => 'Part-time',
            self::Contract => 'Contract',
            self::Temporary => 'Temporary',
            self::Internship => 'Internship',
            self::Volunteer => 'Volunteer',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type): array => [$type->value => $type->label()])
            ->all();
    }
}
