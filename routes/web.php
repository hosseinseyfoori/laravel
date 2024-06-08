<?php

use App\Models\Cardex;
use App\Models\MobileRecive;
use Carbon\Carbon;
use Cryptommer\Smsir\Objects\Parameters;
use Illuminate\Support\Facades\Route;
use Cryptommer\Smsir\Smsir;


//Route::get('/', function () {
//
//    try {
//        $send = smsir::Send();
//        $alarm = Cardex::whereAlarm(now()->toDateString());
//        $mobiles = MobileRecive::whereActive(true)->pluck('mobile')->toArray();
//        $templateId = 695389;
//        foreach ($alarm->get() as $item) {
//            $type = $item->typebimeh->name;
//            $car =  $item->car->name;
//            $code_kargahi =  $item->car->code_kargahi;
//            $expired = jalali($item->expired)->format('Y/m/d');
//            $today = \Illuminate\Support\Carbon::now();
//            $endDate = Carbon::parse($item->expired);
//            $daysRemaining = floor($today->diffInDays($endDate));
//
//            $parameter1 = new Parameters("TYPE", $type);
//            $parameter2 = new Parameters("CAR", $car);
//            $parameter3 = new Parameters("CODE", $code_kargahi);
//            $parameter4 = new Parameters("REMIND",$daysRemaining );
//            $parameters = array($parameter1,$parameter2,$parameter3,$parameter4);
//
//            foreach ($mobiles as $mobile)
//            {
//                $send->Verify($mobile, $templateId, $parameters);
//
//            }
//
//        }
//
//        return 'ok';
//
//    } catch (Exception $exception) {
//        return $exception;
//    }
//
//
//});
Route::get('/date',function (){
    try {
        $send = smsir::Send();

        $alarm = Cardex::where('renew',false)
            ->whereAlarm(now()->toDateString());

        dd($alarm->get());
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
});
