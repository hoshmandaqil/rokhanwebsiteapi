<?php

namespace App\Enums;

enum PlanType: string
{
    case StrategicPlan = 'strategic_plan';
    case ImplementPlan = 'implement_plan';
    case ActionPlan = 'action_plan';
    case DevelopmentPlan = 'development_plan';

    public function label(): string
    {
        return match ($this) {
            self::StrategicPlan => 'Strategic Plan',
            self::ImplementPlan => 'Implement Plan',
            self::ActionPlan => 'Action Plan',
            self::DevelopmentPlan => 'Development Plan',
        };
    }
}
