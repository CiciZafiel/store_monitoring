<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    
    public function index(){
        return view('sales.index');
    }

    public function totalUnpostedToSAP(Request $request)
    {
        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->select('SapDocumentNumber')
        ->whereNull('SapDocumentNumber')
        ->get()
        ->count();
        
        return response()->json($results);
    }

    public function gettotalPostedToSAPToday(Request $request)
    {
        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->select('SapDocumentNumber')
        ->whereNotNull('SapDocumentNumber')
        ->whereRaw('CreationDate = GETDATE()')
        ->get()
        ->count();
        
        return response()->json($results);
    }
    public function gettotalPostedToServerToday(Request $request)
    {
        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->selectRaw('*')
        ->whereRaw('CreationDate = GETDATE()')
        ->get()
        ->count();
        
        return response()->json($results);
    }
    public function gettotalUnpostedToSAPToday(Request $request)
    {
        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->select('SapDocumentNumber')
        ->whereNull('SapDocumentNumber')
        ->whereRaw('CreationDate = GETDATE()')
        ->get()
        ->count();
        
        return response()->json($results);
    }
    public function getSalesData(Request $request, Filter $filter)
    {
        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->select(
            'ID',
            'CreatationDate',
            'CardName',
            'WarehouseCode',
            'Comments',
            'SapDocumentNumber',
            'SapIncomingDocumentNumber')
        if($filter == ""){
            ->whereRaw('CreatationDate = GETDATE()')
        }elseif ($filter == "PostedSAPToday"){
            ->whereRaw('SapDocumentNumber IS NOT NULL AND CreatationDate = GETDATE()')
        }elseif ($filter == "PostedServerToday"){
            ->whereRaw('CreatationDate = GETDATE()')
        }elseif ($filter == "UnpostedSAPToday"){
            ->whereRaw('SapDocumentNumber IS NULL AND CreatationDate = GETDATE()')
        }
        ->get();

        return response()->json($results);
    }
}
