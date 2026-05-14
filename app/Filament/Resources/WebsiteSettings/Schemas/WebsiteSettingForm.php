<?php

namespace App\Filament\Resources\WebsiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WebsiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding & identity')
                    ->description('Shown in the header, footer, and browser metadata.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site / university name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('site_tagline')
                            ->label('Short tagline')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Used in the footer and as default context for visitors.'),
                        FileUpload::make('logo')
                            ->label('Header logo')
                            ->image()
                            ->directory('website-settings')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'])
                            ->maxSize(2048)
                            ->imageEditor()
                            ->nullable()
                            ->helperText('If empty, the site name is shown as text in the navigation.'),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->directory('website-settings')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'])
                            ->maxSize(512)
                            ->nullable()
                            ->helperText('Browser tab icon; square PNG or ICO works best.'),
                    ]),
                Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('contact_phone')
                            ->label('Primary phone')
                            ->tel()
                            ->maxLength(64)
                            ->nullable(),
                        TextInput::make('contact_phone_secondary')
                            ->label('Secondary phone')
                            ->tel()
                            ->maxLength(64)
                            ->nullable(),
                        TextInput::make('contact_whatsapp_url')
                            ->label('WhatsApp link')
                            ->url()
                            ->maxLength(500)
                            ->nullable()
                            ->helperText('Full URL, e.g. https://wa.me/1234567890'),
                        Textarea::make('office_hours')
                            ->label('Office hours')
                            ->rows(3)
                            ->columnSpanFull()
                            ->nullable(),
                    ]),
                Section::make('Address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('address_line_1')
                            ->label('Address line 1')
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpanFull(),
                        TextInput::make('address_line_2')
                            ->label('Address line 2')
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->maxLength(120)
                            ->nullable(),
                        TextInput::make('region')
                            ->label('State / province / region')
                            ->maxLength(120)
                            ->nullable(),
                        TextInput::make('postal_code')
                            ->label('Postal code')
                            ->maxLength(32)
                            ->nullable(),
                        TextInput::make('country')
                            ->maxLength(120)
                            ->nullable(),
                    ]),
                Section::make('Social media')
                    ->description('Full profile URLs. Leave blank to hide an icon.')
                    ->columns(1)
                    ->schema([
                        TextInput::make('social_facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                        TextInput::make('social_instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                        TextInput::make('social_x_url')
                            ->label('X (Twitter)')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                        TextInput::make('social_youtube_url')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                        TextInput::make('social_linkedin_url')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                        TextInput::make('social_tiktok_url')
                            ->label('TikTok')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                    ]),
                Section::make('SEO defaults')
                    ->schema([
                        Textarea::make('meta_default_description')
                            ->label('Default meta description')
                            ->rows(3)
                            ->maxLength(500)
                            ->nullable()
                            ->helperText('Used as a fallback when a page does not define its own description.'),
                    ]),
            ]);
    }
}
