<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogWeightController extends Controller
{
    public function index(Request $request)
    {
        return view('log_weight.index');
    }

    public  function ajax_log_weight(Request $request)
    {
        $date_start = $request->date_start ? date('Y-m-d', strtotime($request->date_start)) : "";
        $date_end = $request->date_end ? date('Y-m-d', strtotime($request->date_end)) : "";
        $scale_name = $request->scale_name;
        $line_number = $request->line_number;

        $data = DB::table('logweight')
            ->when($scale_name, function ($query, $scale_name) {
                return $query->where('timbanganName', 'like', "%{$scale_name}%");
            })
            ->when($line_number, function ($query, $line_number) {
                return $query->where('linenumber', $line_number);
            })
            ->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('createdAt', [$date_start . " 00:00:00", $date_end . " 23:59:59"]);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
