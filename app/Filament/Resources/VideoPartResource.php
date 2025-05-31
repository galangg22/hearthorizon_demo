<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoPartResource\Pages;
use App\Models\VideoPart;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class VideoPartResource extends Resource
{
    protected static ?string $model = VideoPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('video_id')
                    ->label('Video')
                    ->relationship('video', 'title')  // Hubungkan dengan model Video dan tampilkan title
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Menetapkan category_id berdasarkan video_id yang dipilih
                        $video = \App\Models\Video::find($state);
                        if ($video) {
                            $set('category_id', $video->category_id);  // Menetapkan category_id dari video yang dipilih
                        }
                    }),

                Forms\Components\TextInput::make('part_number')
                    ->required() // Pastikan part_number harus diisi
                    ->numeric()  // Pastikan part_number berupa angka
                    ->label('Part Number'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Part Title'),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500),

                Forms\Components\FileUpload::make('thumbnail_path')  // Ganti dengan thumbnail_path
                    ->disk('public')
                    ->directory('parts')  // Menyimpan di folder public/parts
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

                Tables\Columns\ImageColumn::make('thumbnail_path')  // Menampilkan gambar berdasarkan thumbnail_path
                    ->label('Thumbnail'),

                Tables\Columns\TextColumn::make('reference_url')
                    ->url(fn ($record) => $record->reference_url ? $record->reference_url : '#')  // Mengembalikan URL yang valid atau fallback ke '#'
                    ->label('Reference URL')
            ])
            ->filters([ // Filters can be added here if needed
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideoParts::route('/'),
            'create' => Pages\CreateVideoPart::route('/create'),
            'edit' => Pages\EditVideoPart::route('/{record}/edit'),
        ];
    }
}
