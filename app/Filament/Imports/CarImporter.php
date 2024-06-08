<?php

namespace App\Filament\Imports;

use App\Models\Car;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CarImporter extends Importer
{
    protected static ?string $model = Car::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->rules(['max:255']),
            ImportColumn::make('code_kargahi')
                ->rules(['max:255']),
            ImportColumn::make('pelak')
                ->rules(['max:255']),
            ImportColumn::make('shomaremotor')
                ->rules(['max:255']),
            ImportColumn::make('shomareshasi')
                ->rules(['max:255']),
            ImportColumn::make('year')
                ->rules(['max:255']),
            ImportColumn::make('description'),
            ImportColumn::make('active')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Car
    {
        // return Car::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Car();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your car import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
