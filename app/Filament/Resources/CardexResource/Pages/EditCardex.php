<?php

namespace App\Filament\Resources\CardexResource\Pages;

use App\Filament\Resources\CardexResource;
use App\Models\Cardex;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditCardex extends EditRecord
{
    protected static string $resource = CardexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

}
