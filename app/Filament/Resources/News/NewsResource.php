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
use Illuminate\Database\Eloquent\Builder;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Newspaper;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->isAdmin()) {
            return $query;

        }
            return $query->where('author_id', auth()->user()->author->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            Select::make('author_id')
            ->relationship('author', 'username')
            ->required()
            ->options(function() {
                $user = auth()->user();
                if ($user->isAdmin()) {
                    return Author::pluck('username', 'id');
                } elseif ($user->author) {
                    return [$user->author->id => $user->author->username];
                }
                return [];
            })
            ->default(function () {
                $user = auth()->user();
                return $user->author ? $user->author->id : null;
            })
            ->disabled(function() {
                $user = auth()->user();
                return !$user->isAdmin();
            }),

            Select::make('news_category_id')
            ->relationship('newsCategory', 'title')
            ->required(),
            TextInput::make('title')
            ->maxLength(100)
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
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(5120) // 5MB max for news images
                ->imageResizeMode('cover')
                ->imageResizeTargetWidth('1200')
                ->imageResizeTargetHeight('630') // Good for social media sharing
                ->helperText('Upload JPG, PNG, or WebP format. Max 5MB. Recommended: 1200x630px')
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
                TextColumn::make('author.user.name')
                    ->label('Author'),
                TextColumn::make('newsCategory.title'),
                TextColumn::make('title'),
                TextColumn::make('slug'),
                ImageColumn::make('thumbnail')
                    ->getStateUsing(function ($record) {
                        // Use the thumbnail_url accessor from the model
                        return $record->thumbnail_url;
                    })
                    ->label('Thumbnail'),
                ToggleColumn::make('is_featured')
            ])
            ->filters([
                SelectFilter::make('author_id')
                ->relationship('author', 'username')
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
