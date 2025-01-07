<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class BatchProductionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $batch_prod = DB::table('batchproductioncode')->orderBy('idbatchproductioncode', 'desc')->select('*');

            return DataTables::of($batch_prod)
                ->addColumn('action', function ($batch_prod) {
                    return view('datatable-modal._action', [
                        'row_id' => $batch_prod->idbatchproductioncode,
                        'class_edit' => "batch_prod-edit",
                        'class_delete' => "batch_prod-delete",
                        'makeEdit' => $batch_prod->Status == 'created' ? true : false,
                        'makeDelete' => $batch_prod->Status == 'created' ? true : false,
                    ]);
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('batch_prod.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->edit_id;
                if ($id) {
                    $request->validate([
                        'CodeProduction' => "required|unique:batchproductioncode,CodeProduction,{$id},idbatchproductioncode",
                        'codeProductpcs' => 'required',
                        'codeProductBox' => 'required',
                    ]);

                    $data = [
                        'CodeProduction' => $request->CodeProduction,
                        'codeProductpcs' => $request->codeProductpcs,
                        'codeProductBox' => $request->codeProductBox,
                        'Status' => 'created',
                        'operator' => session('fullname')
                    ];

                    DB::table('batchproductioncode')->where('idbatchproductioncode', $id)->update($data);

                    return response()->json([
                        'success' => true,
                        'msg'   => 'Data has been successfully updated!',
                    ]);
                } else {
                    $request->validate([
                        'CodeProduction' => "required|unique:batchproductioncode,CodeProduction",
                        'codeProductpcs' => 'required',
                        'codeProductBox' => 'required',
                    ]);

                    $data = [
                        'CodeProduction' => $request->CodeProduction,
                        'codeProductpcs' => $request->codeProductpcs,
                        'codeProductBox' => $request->codeProductBox,
                        'Status' => 'created',
                        'operator' => session('fullname')
                    ];

                    DB::table('batchproductioncode')->insert($data);

                    return response()->json([
                        'success' => true,
                        'msg'   => 'Data has been successfully created!',
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'msg' => $th->getMessage()
                ]);
            }
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;

                if ($id) {
                    $data = DB::table('batchproductioncode')
                        ->leftJoin('code_product_pcs', 'code_product_pcs.codeproduct', '=', 'batchproductioncode.codeProductpcs')
                        ->leftJoin('code_product_box', 'code_product_box.codeproduct', '=', 'batchproductioncode.codeProductBox')
                        ->where('idbatchproductioncode', $id)
                        ->select('batchproductioncode.*', 'code_product_pcs.namaproduct as namaproduct_pcs', 'code_product_box.namaproduct as namaproduct_box')
                        ->first();

                    if ($data) {
                        return response()->json([
                            'success' => true,
                            'data' => $data
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'msg' => 'Not found !'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Not found !'
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'msg' => $th->getMessage()
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->id;

                if ($id) {
                    $data = DB::table('batchproductioncode')->where('idbatchproductioncode', $id)->first();

                    if ($data) {
                        DB::table('batchproductioncode')->where('idbatchproductioncode', $id)->delete();

                        return response()->json([
                            'success' => true,
                            'msg' => 'Data has been successfully deleted!'
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'msg' => 'Not found !'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Not found !'
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'msg' => $th->getMessage()
                ]);
            }
        }
    }

    public function getProductPcs(Request $request)
    {
        $search = $request->q;

        $data = [];
        if ($request->ajax()) {
            if ($search == '') {
                $data = DB::table('code_product_pcs')->limit(10)->get();
            } else {
                $data = DB::table('code_product_pcs')->where('namaproduct', 'like', "%$search%")->orWhere('codeproduct', 'like', "%$search%")->limit(10)->get();
            }
        }

        return response()->json($data);
    }

    public function getProductBox(Request $request)
    {
        $search = $request->q;

        $data = [];
        if ($request->ajax()) {
            if ($search == '') {
                $data = DB::table('code_product_box')->limit(10)->get();
            } else {
                $data = DB::table('code_product_box')->where('namaproduct', 'like', "%$search%")->orWhere('codeproduct', 'like', "%$search%")->limit(10)->get();
            }
        }

        return response()->json($data);
    }
}
