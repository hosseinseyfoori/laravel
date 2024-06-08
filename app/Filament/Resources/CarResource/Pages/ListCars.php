<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use App\Models\Car;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ImportAction;

class ListCars extends ListRecords
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            \EightyNine\ExcelImport\ExcelImportAction::make()
//                ->color("primary"),
            Actions\CreateAction::make(),
        ];
    }


}
