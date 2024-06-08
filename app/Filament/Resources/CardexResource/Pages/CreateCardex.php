<?php

namespace App\Filament\Resources\CardexResource\Pages;

use App\Filament\Resources\CardexResource;
use App\Models\Cardex;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateCardex extends CreateRecord
{
    protected static string $resource = CardexResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $bimehgar_id = $data['bimehgar_id'];
        $typebimeh_id = $data['typebimeh_id'];
        $car_id = $data['car_id'];
        $start = $data['start'];
        $expired = $data['expired'];
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($expired);


        $cardexLatest = Cardex::query()
            ->where('typebimeh_id', $typebimeh_id)
            ->where('car_id', $car_id)
            ->where('renew', false)->latest()->first();




        if($cardexLatest)
        {
            if (  $endDate < $cardexLatest->expired){
                $message = "بیمه نامه ".$cardexLatest->typebimeh->name.' در حال حاضر تا تاریخ'.jalali($cardexLatest->expired)->format("Y/m/d").'موجود می باشد.';
                Notification::make()
                    ->danger()
                    ->title('خطا ثبت بیمه نامه')
                    ->body($message)
                    ->persistent()
                    ->send();

                $this->halt();
            }
            if ($startDate < $cardexLatest->expired)
            {
                $message =  'بیمه نامه '.$cardexLatest->typebimeh->name.' موجود می باشد'."تاریخ شروع باید از ".jalali($cardexLatest->expired)->format('Y/m/d') . 'بیشتر باشد';
                Notification::make()
                    ->danger()
                    ->title('خطا ثبت بیمه نامه')
                    ->body($message)
                    ->persistent()
                    ->send();
                $this->halt();
            }
        }


        if($cardexLatest){
            $cardexLatest->renew = true;
            $cardexLatest->save();
        }

        return $data;
    }





}
