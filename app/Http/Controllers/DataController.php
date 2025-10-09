<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function averageTemperature()
    {
        $rows = DB::connection('clickhouse')
            ->table('default.noaa')
            ->select('*')
            ->limit(10)
            ->get();

        return response()->json($rows);
    }
}
