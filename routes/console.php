<?php

use App\Models\Cardex;
use App\Models\MobileRecive;
use Cryptommer\Smsir\Objects\Parameters;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Cryptommer\Smsir\Smsir;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::call(function (){

  AlarmExpired::expiredOnDay();
  AlarmExpired::expiredTwoDay();

})->dailyAt("11:05");


Schedule::command('telescope:prune')->daily();


class AlarmExpired {
    public static  function expiredOnDay()
    {
        try {
            $send = smsir::Send();

            $alarm = Cardex::where('renew',false)
            ->whereAlarm(now()->toDateString());

            if($alarm)
            {
                $mobiles = MobileRecive::whereActive(true)->pluck('mobile')->toArray();
                $templateId = 695389;

                foreach ($alarm->get() as $item) {
                    $type = $item->typebimeh->name;
                    $car =  $item->car->name;
                    $code_kargahi =  $item->car->code_kargahi;
                    $expired = jalali($item->expired)->format('Y/m/d');
                    $today = \Illuminate\Support\Carbon::now();
                    $endDate = Carbon::parse($item->expired);
                    $daysRemaining = floor($today->diffInDays($endDate));

                    $parameter1 = new Parameters("TYPE", $type);
                    $parameter2 = new Parameters("CAR", $car);
                    $parameter3 = new Parameters("CODE", $code_kargahi);
                    $parameter4 = new Parameters("REMIND",$daysRemaining );
                    $parameters = array($parameter1,$parameter2,$parameter3,$parameter4);

                    foreach ($mobiles as $mobile)
                    {
                        $send->Verify($mobile, $templateId, $parameters);

                    }
                }
                return true;
            }
            return false;

        } catch (Exception $exception) {
            return $exception;
        }
    }

    public static function expiredTwoDay()
    {
        try {
            $send = smsir::Send();
            $alarm = Cardex::where('renew',false)
                ->whereExpired(now()->addDay(2)->toDateString());

            if($alarm)
            {
                $mobiles = MobileRecive::whereActive(true)->pluck('mobile')->toArray();
                $templateId = 695389;
                foreach ($alarm->get() as $item) {
                    $type = $item->typebimeh->name;
                    $car =  $item->car->name;
                    $code_kargahi =  $item->car->code_kargahi;
                    $expired = jalali($item->expired)->format('Y/m/d');
                    $today = \Illuminate\Support\Carbon::now();
                    $endDate = Carbon::parse($item->expired);
                    $daysRemaining = floor($today->diffInDays($endDate));

                    $parameter1 = new Parameters("TYPE", $type);
                    $parameter2 = new Parameters("CAR", $car);
                    $parameter3 = new Parameters("CODE", $code_kargahi);
                    $parameter4 = new Parameters("REMIND",$daysRemaining );
                    $parameters = array($parameter1,$parameter2,$parameter3,$parameter4);

                    foreach ($mobiles as $mobile)
                    {
                        $send->Verify($mobile, $templateId, $parameters);
                    }
                }
                return true;
            }
            return false;


        } catch (Exception $exception) {
            return $exception;
        }
    }
}
