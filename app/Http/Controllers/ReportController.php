<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('report.index');
    }

    public function ajax_get_report(Request $request)
    {
        $dateStart = date('Y-m-d', strtotime($request->date_start));
        $dateEnd = date('Y-m-d', strtotime($request->date_end));

        if ($dateStart && $dateEnd) {
            $data = DB::table('batchproductioncode')
                ->where('Status', 'Finish')
                ->whereBetween('starproduction', ["{$dateStart} 00:00:00", "{$dateEnd} 23:59:59"])
                ->get();
        } else {
            $data = [];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
