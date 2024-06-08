<?php

namespace App\Filament\Resources\BimehgarResource\Pages;

use App\Filament\Resources\BimehgarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBimehgars extends ListRecords
{
    protected static string $resource = BimehgarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
