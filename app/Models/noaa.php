<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class noaa extends Model
{
    protected $columns =[
        "station_id",
        "tempAvg"
    ];
}
