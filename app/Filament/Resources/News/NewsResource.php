<?php

namespace App\Filament\Resources\News;

use BackedEnum;
use App\Models\News;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Schemas\NewsForm;
use App\Filament\Resources\News\Tables\NewsTable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;

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
             ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
            ->required(),
            TextInput::make('slug')
            ->readOnly(),
            FileUpload::make('thumbnail')
            ->image()
            ->required(),
            RichEditor::make('content')
            ->required(),

    
        ]);
    }

    public static function table(Table $table): Table
    {
        return NewsTable::configure($table);
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
