<?php

namespace App\Filament\Resources\VideoPartResource\Pages;

use App\Filament\Resources\VideoPartResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoParts extends ListRecords
{
    protected static string $resource = VideoPartResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
