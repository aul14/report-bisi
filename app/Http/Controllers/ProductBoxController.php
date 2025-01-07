<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ProductBoxController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $box = DB::table('code_product_box')->orderBy('idcode_product_box', 'desc')->select('*');

            return DataTables::of($box)
                ->addColumn('action', function ($box) {
                    return view('datatable-modal._action', [
                        'row_id' => $box->idcode_product_box,
                        'class_edit' => "box-edit",
                        'class_delete' => "box-delete",
                        'makeEdit' => true,
                        'makeDelete' => true,
                    ]);
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('product_box.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id = $request->edit_id;
                if ($id) {
                    $request->validate([
                        'namaproduct' => 'required',
                        'codeproduct' => "required|unique:code_product_box,codeproduct,{$id},idcode_product_box",
                        'indexs' => "required|unique:code_product_box,indexs,{$id},idcode_product_box",
                    ]);

                    $data = [
                        'namaproduct' => $request->namaproduct,
                        'codeproduct' => $request->codeproduct,
                        'indexs' => $request->indexs,
                        'lowerLimit' => $request->lowerLimit,
                        'uperlimit' => $request->uperlimit,
                        'nominalvalue' => $request->nominalvalue,
                    ];

                    DB::table('code_product_box')->where('idcode_product_box', $id)->update($data);

                    return response()->json([
                        'success' => true,
                        'msg'   => 'Data has been successfully updated!',
                    ]);
                } else {
                    $request->validate([
                        'namaproduct' => 'required',
                        'codeproduct' => 'required|unique:code_product_box,codeproduct',
                        'indexs' => 'required|unique:code_product_box,indexs',
                    ]);

                    $data = [
                        'namaproduct' => $request->namaproduct,
                        'codeproduct' => $request->codeproduct,
                        'indexs' => $request->indexs,
                        'lowerLimit' => $request->lowerLimit,
                        'uperlimit' => $request->uperlimit,
                        'nominalvalue' => $request->nominalvalue,
                    ];

                    DB::table('code_product_box')->insert($data);

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
                    $data = DB::table('code_product_box')->where('idcode_product_box', $id)->first();

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
                    $data = DB::table('code_product_box')->where('idcode_product_box', $id)->first();

                    if ($data) {
                        DB::table('code_product_box')->where('idcode_product_box', $id)->delete();

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
