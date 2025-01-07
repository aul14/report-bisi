<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProductPcsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pcs = DB::table('code_product_pcs')->orderBy('idcode_product_pcs', 'desc')->select('*');

            return DataTables::of($pcs)
                ->addColumn('action', function ($pcs) {
                    return view('datatable-modal._action', [
                        'row_id' => $pcs->idcode_product_pcs,
                        'class_edit' => "pcs-edit",
                        'class_delete' => "pcs-delete",
                        'makeEdit' => true,
                        'makeDelete' => true,
                    ]);
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('product_pcs.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->edit_id;
                if ($id) {
                    $request->validate([
                        'namaproduct' => 'required',
                        'codeproduct' => "required|unique:code_product_pcs,codeproduct,{$id},idcode_product_pcs",
                        'indexs' => "required|unique:code_product_pcs,indexs,{$id},idcode_product_pcs",
                    ]);

                    $data = [
                        'namaproduct' => $request->namaproduct,
                        'codeproduct' => $request->codeproduct,
                        'indexs' => $request->indexs,
                        'lowerLimit' => $request->lowerLimit,
                        'uperlimit' => $request->uperlimit,
                        'nominalvalue' => $request->nominalvalue,
                    ];

                    DB::table('code_product_pcs')->where('idcode_product_pcs', $id)->update($data);

                    return response()->json([
                        'success' => true,
                        'msg'   => 'Data has been successfully updated!',
                    ]);
                } else {
                    $request->validate([
                        'namaproduct' => 'required',
                        'codeproduct' => 'required|unique:code_product_pcs,codeproduct',
                        'indexs' => 'required|unique:code_product_pcs,indexs',
                    ]);

                    $data = [
                        'namaproduct' => $request->namaproduct,
                        'codeproduct' => $request->codeproduct,
                        'indexs' => $request->indexs,
                        'lowerLimit' => $request->lowerLimit,
                        'uperlimit' => $request->uperlimit,
                        'nominalvalue' => $request->nominalvalue,
                    ];

                    DB::table('code_product_pcs')->insert($data);

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
                    $data = DB::table('code_product_pcs')->where('idcode_product_pcs', $id)->first();

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
                    $data = DB::table('code_product_pcs')->where('idcode_product_pcs', $id)->first();

                    if ($data) {
                        DB::table('code_product_pcs')->where('idcode_product_pcs', $id)->delete();

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
}
