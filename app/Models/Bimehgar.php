<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bimehgar extends Model
{
    use HasFactory;

    protected $table = "bimehgar";
    protected $guarded = [];

    public function shbimeh()
    {
        return $this->belongsTo(ShBimeh::class, 'shbimeh_id');
    }

    public function fullname()
    {
        return $this->name.' '.$this->family;
    }

    public function cardexs():HasMany
    {
        return $this->hasMany(Cardex::class, 'bimehgar_id');
    }


}
