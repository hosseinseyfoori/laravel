<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cardexs():HasMany
    {
        return $this->hasMany(Cardex::class,'car_id');
    }

    public function withCodeKargahi()
    {
        return $this->name . ' ' . $this->code_kargahi;
    }
}
