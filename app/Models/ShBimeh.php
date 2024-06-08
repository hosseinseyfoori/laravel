<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShBimeh extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'shbimeh';

    public function getNameAttribute() // notice that the attribute name is in CamelCase.
    {
        return $this->attributes['name'];
    }

    public function nameAndCity()
    {
        return $this->attributes['name'] . ' - ' . $this->attributes['city'];

    }

}
