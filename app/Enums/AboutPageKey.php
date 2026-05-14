<?php

namespace App\Enums;

/**
 * Stable keys for public About menu pages (matches website URLs under /about/...).
 */
enum AboutPageKey: string
{
    case AboutUs = 'about-us';
    case ChairmanMessage = 'chairman-message';
    case ChancellorMessage = 'chancellor-message';
    case ViceChancellorMessage = 'vice-chancellor-message';
    case MissionAndVision = 'mission-and-vision';
    case History = 'history';
    case StrategicPlan = 'strategic-plan';
    case BoardOfTrustees = 'board-of-trustees';
    case FoundersMessage = 'founders-message';
    case MemorandumsOfUnderstanding = 'memorandums-of-understanding';

    public function label(): string
    {
        return match ($this) {
            self::AboutUs => 'About Us',
            self::ChairmanMessage => 'Chairman Message',
            self::ChancellorMessage => 'Chancellor Message',
            self::ViceChancellorMessage => 'Vice Chancellor Message',
            self::MissionAndVision => 'Mission And Vision',
            self::History => 'History',
            self::StrategicPlan => 'Strategic Plan',
            self::BoardOfTrustees => 'Board Of Trustees',
            self::FoundersMessage => "Founders' Message",
            self::MemorandumsOfUnderstanding => 'Memorandums Of Understanding',
        };
    }

    public function navigationSort(): int
    {
        return match ($this) {
            self::AboutUs => 11,
            self::ChairmanMessage => 12,
            self::ChancellorMessage => 13,
            self::ViceChancellorMessage => 14,
            self::MissionAndVision => 15,
            self::History => 16,
            self::StrategicPlan => 17,
            self::BoardOfTrustees => 18,
            self::FoundersMessage => 19,
            self::MemorandumsOfUnderstanding => 20,
        };
    }

    /**
     * @return list<string>
     */
    public static function orderedValues(): array
    {
        return array_map(
            static fn (self $case) => $case->value,
            self::cases(),
        );
    }
}
