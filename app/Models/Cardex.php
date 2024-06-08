<?php

namespace App\Models;

use DateTime;
use Hekmatinasser\Jalali\Jalali;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cardex extends Model
{
    use HasFactory,SoftDeletes;

    protected $casts = [
        'image' => 'array',
    ];



    protected $guarded = [];

    public function typebimeh()
    {
     return   $this->belongsTo(TypeBimeh::class, 'typebimeh_id');
    }
    public function shbimeh()
    {
       return $this->belongsTo(ShBimeh::class, 'shbimeh_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bimehgar()
    {
        return $this->belongsTo(BimehGar::class, 'bimehgar_id');
    }

//    protected $casts = [
//        'start' => 'timestamp',
//        'expired' => 'timestamp',
//    ];


    public function getRemind()
    {
        $dateTimeObject = new DateTime();
        $otherDateTimeObject = new DateTime($this->attributes['expired']);
        $diff = $otherDateTimeObject->diff($dateTimeObject);
        return $diff;
    }



//    public function getStartAttribute()
//    {
//        $date = $this->attributes['start'];
//        $jalaliDate = Jalali::instance($date);
//
//
//        return $jalaliDate->format('Y/m/d');
//    }
    public function getEndAttribute()
    {

        $targetDate = Verta::instance($this->attributes['expired']);
        $today = Verta::now();
        return $targetDate->diffDays($today);

    }
}
