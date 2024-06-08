<?php

namespace App\Filament\Resources\CardexResource\Widgets;

use App\Models\Cardex;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CardexOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('کل بیمه نامه ها', Cardex::where('renew',false)->count()),

        ];
    }
}
