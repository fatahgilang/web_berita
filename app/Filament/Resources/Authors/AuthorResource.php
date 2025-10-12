<?php

namespace App\Filament\Resources\Authors;

use BackedEnum;
use App\Models\Author;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\Authors\Pages\EditAuthor;
use App\Filament\Resources\Authors\Pages\ListAuthors;
use App\Filament\Resources\Authors\Pages\CreateAuthor;
use App\Filament\Resources\Authors\Schemas\AuthorForm;
use App\Filament\Resources\Authors\Tables\AuthorsTable;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static ?string $recordTitleAttribute = 'AuthorResource';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            TextInput::make('name')
            ->required(),
            TextInput::make('username')
            ->required(),
            FileUpload::make('avatar')
                ->image()
                ->disk('public')
                ->directory('avatars')
                ->visibility('public')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                ->maxSize(2048) // 2MB max
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->imageResizeTargetWidth('400')
                ->imageResizeTargetHeight('400')
                ->helperText('Upload JPG, PNG, or WebP format. Max 2MB. HEIC format is not supported.')
                ->required(),
            Textarea::make('bio')
            ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            ImageColumn::make('avatar')
                ->getStateUsing(function ($record) {
                    // Use the avatar_url accessor from the model
                    // This handles HEIC format and missing files automatically
                    return $record->avatar_url;
                })
                ->circular()
                ->defaultImageUrl(asset('img/profile.svg'))
                ->label('Avatar'),
            TextColumn::make('name')
            ->searchable()
            ->sortable(),
            TextColumn::make('username')
            ->searchable()
            ->sortable(),
            TextColumn::make('bio')
            ->limit(50)
            ->searchable()
            ->sortable(),
        ])
        ->filters([
                //
            ])
            ->actions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuthors::route('/'),
            'create' => CreateAuthor::route('/create'),
            'edit' => EditAuthor::route('/{record}/edit'),
        ];
    }
}
