<?php

namespace App\Filament\Resources\Users;

use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';
    
     public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema 
        ->schema([
            TextInput::make('name')
            ->label('Name')
            ->required()
            ->maxlength(255),
            TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->maxlength(255)
           ->unique(ignoreRecord: true),
           Select::make('role')
           ->label('Role')
           ->options([
               'admin' => 'Admin',
               'author' => 'Author',
           ])
           ->required()
           ->default('author'),
           TextInput::make('password')
           ->label('Password')
           ->password()
           ->required(fn(string $context): bool => $context === 'create')
           ->minlength(8)
           ->dehydrated(fn($state) =>filled($state))
           ->dehydrateStateUsing(fn($state) => Hash::make($state)),
           Toggle::make('email_verified_at')
           ->label('Email Terverifikasi')
           ->dehydrated(false)
           ->afterStateHydrated(function ($component,$state) {
            $component->state($state !== null);
           }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make ('name')
            ->label('Name')
            ->searchable()
            ->sortable(),
            TextColumn::make ('email')
            ->label('Email')
            ->searchable()
            ->sortable(),
            TextColumn::make ('role')
            ->label('Role')
            ->badge()
            ->color(fn(string $state): string => match($state) {
                'admin' => 'danger',
                'author' => 'success',
            
            }),
            TextColumn::make ('author.username')
            ->label('Username')
            ->sortable(),
            TextColumn::make('created_at')
            ->label('Dibuat')
            ->dateTime('d-M-Y')
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
