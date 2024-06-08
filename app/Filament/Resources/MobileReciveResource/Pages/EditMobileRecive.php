<?php

namespace App\Filament\Resources\MobileReciveResource\Pages;

use App\Filament\Resources\MobileReciveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobileRecive extends EditRecord
{
    protected static string $resource = MobileReciveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
