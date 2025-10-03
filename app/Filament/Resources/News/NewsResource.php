<?php

namespace App\Filament\Resources\News;

use BackedEnum;
use App\Models\News;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use function Laravel\Prompts\select;

use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Schemas\NewsForm;
use App\Filament\Resources\News\Tables\NewsTable;
use Filament\Tables\Columns\ToggleColumn;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Newspaper;

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            Select::make('author_id')
            ->relationship('author', 'name')
            ->required(),
            Select::make('news_category_id')
            ->relationship('newsCategory', 'title')
            ->required(),
            TextInput::make('title')
            ->maxLength(50)
             ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
            ->required(),
            TextInput::make('slug')
            ->readOnly(),
            FileUpload::make('thumbnail')
            ->image()
            ->disk('public')
            ->directory('thumbnails')
            ->visibility('public')
            ->required()
            ->columnSpanFull(),
            RichEditor::make('content')
            ->required()
            ->columnSpanFull(),
            Toggle::make('is_featured')



        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author.name'),
                TextColumn::make('newsCategory.title'),
                TextColumn::make('title'),
                TextColumn::make('slug'),
                ImageColumn::make('thumbnail')
                ->getStateUsing(function ($record) {
                    $path = $record->thumbnail ?? '';
                    if ($path === '') {
                        return null;
                    }
                    if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                        return $path;
                    }
                    if (\Illuminate\Support\Str::startsWith($path, ['/storage/'])) {
                        // Already a public path
                        return $path;
                    }
                    return asset('storage/' . ltrim($path, '/'));
                }),
                ToggleColumn::make('is_featured')
            ])
            ->filters([
                SelectFilter::make('author_id')
                ->relationship('author', 'name')
                ->label('Select Author'),
                SelectFilter::make('news_category_id')
                ->relationship('newsCategory', 'title')
                ->label('Select Category'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    // \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => ListNews::route('/'),
            'create' => CreateNews::route('/create'),
            'edit' => EditNews::route('/{record}/edit'),
        ];
    }
}
