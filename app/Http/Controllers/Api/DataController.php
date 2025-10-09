<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $offset = ($page - 1) * 10;

        $rows = DB::connection('clickhouse')
            ->table('default.noaa')
            ->select('*');

        if ($request->has('startDate') && !empty($request->startDate)) {
            $rows->whereRaw("date >= '{$request->startDate}'");
        }

        if ($request->has('endDate') && !empty($request->endDate)) {
            $rows->whereRaw("date <= '{$request->endDate}'");
        }

        if ($request->has('area')) {
            $rows->whereRaw("name like '%{$request->area}%'");
        }

        $count = $rows->count();
        $totalPages = (int) ceil($count / 10);

        $rows->limit(10, $offset);

        return response()->json(['rows' => $rows->get(), 'totalPages' => $totalPages]);
    }

    public function getArea()
    {
        $area = DB::connection('clickhouse')
            ->table('default.noaa')
            ->select('name')
            ->groupBy('name')
            ->whereRaw("name != ''")
            ->limit(30)
            ->get();

        return response()->json($area);
    }

    public function getYearAvg(Request $request) {
        $data = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('year(date) as year'), raw('AVG(tempMax) as max_temp'), raw('AVG(tempMin) as min_temp'));
        // ->where('year', '>=', 1944)

        if ($request->has('startDate') && !empty($request->startDate)) {
            $data->whereRaw("date >= '{$request->startDate}'");
        }

        if ($request->has('endDate') && !empty($request->endDate)) {
            $data->whereRaw("date <= '{$request->endDate}'");
        }

        if ($request->has('area')) {
            $data->whereRaw("name like '%{$request->area}%'");
        }

        $data->groupBy('year');

        return response()->json($data->get());
    }

    public function getYearExtrem(Request $request) {
        $data = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('year(date) as year'), raw('COUNT(CASE WHEN precipitation > 0 THEN 1 END) AS rain_day'), raw('COUNT(CASE WHEN snowfall > 0 THEN 1 END) AS snow_day'), raw('COUNT(CASE WHEN maxWindSpeed > 50 THEN 1 END) AS wind_day'));
        // ->where('year', '>=', 1944)

        if ($request->has('startDate') && !empty($request->startDate)) {
            $data->whereRaw("date >= '{$request->startDate}'");
        }

        if ($request->has('endDate') && !empty($request->endDate)) {
            $data->whereRaw("date <= '{$request->endDate}'");
        }

        if ($request->has('area')) {
            $data->whereRaw("name like '%{$request->area}%'");
        }

        $data->groupBy('year');

        return response()->json($data->get());
    }

    public function getSummary(Request $request) {
        $max = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('toYear(date) AS year'), raw('AVG(tempMax) AS value'));

        $min = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('toYear(date) AS year'), raw('AVG(tempMin) AS value'));

        $rain = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('toYear(date) AS year'), raw('COUNT(*) AS value'));

        $snow = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('toYear(date) AS year'), raw('COUNT(*) AS value'));

        $wind = DB::connection('clickhouse')
        ->table('default.noaa')
        ->select(raw('toYear(date) AS year'), raw('COUNT(*) AS value'));

        if ($request->has('startDate') && !empty($request->startDate)) {
            $max->whereRaw("date >= '{$request->startDate}'");
            $min->whereRaw("date >= '{$request->startDate}'");
            $rain->whereRaw("date >= '{$request->startDate}'");
            $snow->whereRaw("date >= '{$request->startDate}'");
            $wind->whereRaw("date >= '{$request->startDate}'");
        }

        if ($request->has('endDate') && !empty($request->endDate)) {
            $max->whereRaw("date <= '{$request->endDate}'");
            $min->whereRaw("date <= '{$request->endDate}'");
            $rain->whereRaw("date <= '{$request->endDate}'");
            $snow->whereRaw("date <= '{$request->endDate}'");
            $wind->whereRaw("date <= '{$request->endDate}'");
        }

        if ($request->has('area')) {
            $max->whereRaw("name like '%{$request->area}%'");
            $min->whereRaw("name like '%{$request->area}%'");
            $rain->whereRaw("name like '%{$request->area}%'");
            $snow->whereRaw("name like '%{$request->area}%'");
            $wind->whereRaw("name like '%{$request->area}%'");
        }

        $rain->where('precipitation', '>', 0);
        $snow->where('snowfall', '>', 0);
        $wind->where('maxWindSpeed', '>', 50);

        $max->groupBy('year')->orderByDesc('value')->limit(1);
        $min->groupBy('year')->orderBy('value')->limit(1);
        $rain->groupBy('year')->orderByDesc('value')->limit(1);
        $snow->groupBy('year')->orderByDesc('value')->limit(1);
        $wind->groupBy('year')->orderByDesc('value')->limit(1);

        return response()->json([
            "max" => $max->get(),
            "min" => $min->get(),
            "rain" => $rain->get(),
            "snow" => $snow->get(),
            "wind" => $wind->get(),
        ]);
    }
}
