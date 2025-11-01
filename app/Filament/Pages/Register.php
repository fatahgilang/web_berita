<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Models\User;
use App\Models\Author;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    public function form(Schema $form): Schema
    {
        return $form
        ->schema([
            $this->getNameFormComponent(),
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->maxLength(255),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
            FileUpload::make('avatar')
                ->label('Avatar')
                ->required()
                ->image()
                ->disk('public')
                ->directory('avatars'),
            Textarea::make('bio')
                ->label('Bio')
                ->required()
                ->maxLength(1000)
                ->rows(3),
        ])
        ->statePath('data');
    }
    
    protected function handleRegistration(array $data): User
    {
        // Create the user (password is already hashed by the form component)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Already hashed by Filament's form component
            'role' => 'author', // Default role for registered users
        ]);
        
        // Create the author profile
        Author::create([
            'user_id' => $user->id,
            'username' => $data['username'],
            'avatar' => $data['avatar'],
            'bio' => $data['bio'],
        ]);
        
        return $user;
    }
}