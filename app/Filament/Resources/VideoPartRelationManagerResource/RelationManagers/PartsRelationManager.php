<?php

namespace App\Filament\Resources\VideoPartRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartsRelationManager extends RelationManager
{
    protected static string $relationship = 'parts';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Select::make('video_id')  // Pilih Video
                ->label('Video')
                ->relationship('video', 'title')  // Menghubungkan ke model Video
                ->required(),

            Forms\Components\TextInput::make('part_number')
                ->required()
                ->numeric()
                ->label('Part Number'),

            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->label('Part Title'),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->maxLength(500),

            Forms\Components\FileUpload::make('thumbnail')
                ->disk('public')
                ->directory('parts')
                ->label('Thumbnail')
                ->required()
                ->image(),

            Forms\Components\TextInput::make('reference_url')
                ->label('Reference URL')
                ->url()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('part_number')
                    ->sortable()
                    ->label('Part Number'),
                
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->label('Part Title'),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),
                
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail'),

                Tables\Columns\TextColumn::make('reference_url')
                    ->url(function ($record) {
                        return $record->reference_url ? $record->reference_url : '#'; // Menangani URL yang kosong
                    })
                    ->label('Reference URL'),

            ])
            ->filters([
                // Filter if needed
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
