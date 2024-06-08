<?php

namespace App\Filament\Resources\ShBimehResource\Pages;

use App\Filament\Resources\ShBimehResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateShBimeh extends CreateRecord
{
    protected static string $resource = ShBimehResource::class;
//    protected function getCreatedNotificationTitle(): ?string
//    {
//       return "شرکت بیمه جدید اضافه شد.";
//    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('شرکت بیمه')
            ->body('با موفقیت ثبت شد');
    }
}
