<?php

namespace App\Filament\Resources\BimehgarResource\Pages;

use App\Filament\Resources\BimehgarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBimehgar extends EditRecord
{
    protected static string $resource = BimehgarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
