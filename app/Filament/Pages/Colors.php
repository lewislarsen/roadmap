<?php

namespace App\Filament\Pages;

use App\Enums\UserRole;
use App\Settings\ColorSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Card;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Colors extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static string $settings = ColorSettings::class;

    protected static ?string $navigationLabel = 'Theme';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(UserRole::Admin);
    }

    public function mount(): void
    {
        parent::mount();

        abort_unless(auth()->user()->hasRole(UserRole::Admin), 403);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                FileUpload::make('logo')
                    ->image()
                    ->helperText('Make sure your storage is linked (by running php artisan storage:link).')
                    ->disk('public')
//                    ->imageResizeTargetHeight('64')
                    ->maxSize(1024)
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) str($file->getClientOriginalName())->prepend('logo-');
                    })
                    ->getUploadedFileNameForStorageUsing(function ($record) {
                        return storage_path('app/public/'.app(ColorSettings::class)->logo);
                    }),
                FileUpload::make('favicon')
                    ->image()
                    ->disk('public')
//                    ->imageResizeTargetHeight('64')
//                    ->imageResizeTargetWidth('64')
                    ->maxSize(1024)
                    ->getUploadedFileNameForStorageUsing(function ($record) {
                        return storage_path('app/public/favicon.png');
                    })
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string)'favicon.png';
                    }),
                TextInput::make('fontFamily')
                    ->placeholder('e.g. Roboto')
                    ->required()
                    ->helperText(new HtmlString('Choose a font family from <a href="https://fonts.bunny.net" target="_blank" rel="noreferrer">Bunny Fonts</a> (e.g. \'Roboto\')')),
                ColorPicker::make('primary')
            ])->columns(),
        ]);
    }
}
