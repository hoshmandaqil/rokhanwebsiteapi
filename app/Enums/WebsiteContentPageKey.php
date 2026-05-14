<?php

namespace App\Enums;

enum WebsiteContentPageKey: string
{
    case QaAbout = 'quality-assurance-about';
    case QaPolicyAndPlan = 'quality-assurance-policy-and-plan';
    case QaStructure = 'quality-assurance-structure';
    case ResearchOverview = 'research-overview';
    case ResearchStructure = 'research-structure';
    case ResearchPolicy = 'research-policy';
    case ResearchPublications = 'research-publications';
    case ResearchJournals = 'research-journals';

    public function label(): string
    {
        return match ($this) {
            self::QaAbout => 'Quality Assurance - About',
            self::QaPolicyAndPlan => 'Quality Assurance - Policy And Plan',
            self::QaStructure => 'Quality Assurance - Structure',
            self::ResearchOverview => 'Research - Overview',
            self::ResearchStructure => 'Research - Structure',
            self::ResearchPolicy => 'Research - Policy',
            self::ResearchPublications => 'Research - Publications',
            self::ResearchJournals => 'Research - Journals',
        };
    }

    public function websitePath(): string
    {
        return match ($this) {
            self::QaAbout => '/quality-assurance/about',
            self::QaPolicyAndPlan => '/quality-assurance/policy-and-plan',
            self::QaStructure => '/quality-assurance/structure',
            self::ResearchOverview => '/research',
            self::ResearchStructure => '/research/structure',
            self::ResearchPolicy => '/research/policy',
            self::ResearchPublications => '/research/publications',
            self::ResearchJournals => '/research/journals',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
