<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\Login as BasePage;

class Login extends BasePage
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->autofocus(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'data.username' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
