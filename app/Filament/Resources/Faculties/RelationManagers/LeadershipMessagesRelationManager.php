<?php

namespace App\Filament\Resources\Faculties\RelationManagers;

use App\Enums\LeadershipMessageType;
use App\Filament\Resources\LeadershipMessages\LeadershipMessageResource;
use App\Filament\Resources\LeadershipMessages\Schemas\LeadershipMessageForm;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;

class LeadershipMessagesRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'leadershipMessages';

    protected static ?string $relatedResource = LeadershipMessageResource::class;

    protected static ?string $title = 'Leadership messages';

    #[Reactive]
    public ?string $activeLocale = null;

    public function mount(): void
    {
        parent::mount();
        $this->mountTranslatable();
    }

    public function form(Schema $schema): Schema
    {
        return LeadershipMessageForm::configureForFacultyRelation($schema, (int) $this->getOwnerRecord()->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('type', LeadershipMessageType::Faculty))
            ->headerActions([
                LocaleSwitcher::make(),
            ]);
    }
}
