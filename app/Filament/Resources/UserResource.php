<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\{TextInput, Select, Checkbox};
use Filament\Tables\Columns\{TextColumn, IconColumn};
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            TextInput::make('phone_number')->required(),
            Select::make('role')->options([
                'admin' => 'Admin',
                'user' => 'User',
            ])->required(),
            Checkbox::make('is_blocked')->label('Blocked')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone_number'),
                TextColumn::make('role'),
                IconColumn::make('is_blocked')->label('Blocked')->boolean(),
            ])
            ->actions([
                Action::make('toggleBlock')
                    ->label(fn (User $record) => $record->is_blocked ? 'Unblock' : 'Block')
                    ->color(fn (User $record) => $record->is_blocked ? 'success' : 'danger')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['is_blocked' => !$record->is_blocked]);
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

