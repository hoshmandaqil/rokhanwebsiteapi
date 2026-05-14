<?php

namespace App\Filament\Resources\WebsiteContentPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Concerns\ResolvesAboutFilamentRecordTitle;
use App\Filament\Resources\WebsiteAboutPages\Schemas\WebsiteAboutPageForm;
use App\Filament\Resources\WebsiteAboutPages\Tables\WebsiteAboutPageTable;
use App\Filament\Resources\WebsiteContentPages\Concerns\ManagesSingletonWebsiteContentPage;
use App\Models\About;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

abstract class BaseWebsiteContentPageResource extends Resource
{
    use ManagesSingletonWebsiteContentPage;
    use ResolvesAboutFilamentRecordTitle;
    use Translatable;

    protected static ?string $model = About::class;

    protected static ?string $recordTitleAttribute = 'title';

    abstract public static function pageKey(): string;

    public static function form(Schema $schema): Schema
    {
        $publicPath = WebsiteContentPageKey::from(static::pageKey())->websitePath();

        return WebsiteAboutPageForm::configure($schema, $publicPath);
    }

    public static function table(Table $table): Table
    {
        return WebsiteAboutPageTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return static::getNavigationLabel() ?? 'Page';
    }

    public static function getPluralModelLabel(): string
    {
        return static::getNavigationLabel() ?? 'Pages';
    }
}
