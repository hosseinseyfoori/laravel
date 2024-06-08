<?php

namespace App\Filament\Resources\CardexResource\Pages;

use App\Filament\Resources\CardexResource;
use App\Models\Cardex;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;


class ListCardexes extends ListRecords
{
    protected static string $resource = CardexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CardexResource\Widgets\CardexOverview::class,

        ];
    }

    public function getTabs(): array
    {
        return [
            'همه' => Tab::make(),
            'این هفته' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->where('renew',false)
                    ->where('expired', '<=', Carbon::now()->addWeeks());
            })->badge(Cardex::query()
                ->where('renew',false)
                ->where('expired', '<=', Carbon::now()->addWeeks())->count()),
            'این ماه' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->where('renew',false)
                    ->where('expired', '<=', now()->addMonth());
            })->badge(Cardex::query()
                ->where('renew',false)
                ->where('expired', '<=', now()->addMonth())->count()),


        ];
    }


}
