<?php

namespace App\Filament\Resources\TypeBimehResource\Pages;

use App\Filament\Resources\TypeBimehResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeBimeh extends EditRecord
{
    protected static string $resource = TypeBimehResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
