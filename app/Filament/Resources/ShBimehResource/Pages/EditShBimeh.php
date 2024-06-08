<?php

namespace App\Filament\Resources\ShBimehResource\Pages;

use App\Filament\Resources\ShBimehResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShBimeh extends EditRecord
{
    protected static string $resource = ShBimehResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
