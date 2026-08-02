<?php

namespace App\Filament\Resources\Departments\RelationManagers;

use App\Enums\PlanScope;
use App\Filament\Resources\Plans\PlanResource;
use App\Filament\Resources\Plans\Schemas\PlanForm;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;

class PlansRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'plans';

    protected static ?string $relatedResource = PlanResource::class;

    protected static ?string $title = 'Strategic & implementation plans';

    // Reactive locale updates remount lazy RMs before $table is initialized.
    protected static bool $isLazy = false;

    #[Reactive]
    public ?string $activeLocale = null;

    public function mount(): void
    {
        parent::mount();
        $this->mountTranslatable();
    }

    public function form(Schema $schema): Schema
    {
        return PlanForm::configureForDepartmentRelation($schema, (int) $this->getOwnerRecord()->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('scope', PlanScope::Department))
            ->headerActions([
                LocaleSwitcher::make(),
            ]);
    }
}
