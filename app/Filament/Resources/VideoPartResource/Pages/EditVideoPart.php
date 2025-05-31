<?php

namespace App\Filament\Resources\VideoPartResource\Pages;

use App\Filament\Resources\VideoPartResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoPart extends EditRecord
{
    protected static string $resource = VideoPartResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
