<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    
    
    public function getSalesData(Request $request)
    {   
        $query = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->select(
            'ID',
            DB::raw("FORMAT (CreationDate, 'MM-dd-yyyy ') as CreationDate"),
            'CardName',
            'WarehouseCode',
            'Comments',
            'CustomerReferenceNumber',
            'SapDocumentNumber',
            'SapIncomingDocumentNumber'
        );

        

        switch ($request['type']) {
            case 'sap_unposted':
                $query->whereNull('SapDocumentNumber');

                break;
            case 'sap_posted_today':
                $query->whereNotNull('SapDocumentNumber')
                ->whereRaw('CreationDate = GETDATE()');

                break;
            case 'server_unposted_today':
                $query->whereRaw('CreationDate = GETDATE()');

                break;
            case 'sap_unposted_today':
                $query->whereNull('SapDocumentNumber')
                ->whereRaw('CreationDate = GETDATE()');

                break;            
            default:
                break;
        }

        if($request['searchThis'] != null){         
            
            $query->where(function($qry) use($request){
                $qry->orWhere('ID','like', '%' .$request['searchThis']. '%')
                    ->orWhere('CardName', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('WarehouseCode', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('CustomerReferenceNumber', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('SapDocumentNumber', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('SapIncomingDocumentNumber', 'like', '%'.$request['searchThis'].'%');
            });
        }

        $results = $query->orderBy('CreationDate', 'DESC')
        ->paginate(10);
       
        return response()->json($results);
    }

    public function getSalesPostedAndUnpostedSummary()
    { 
        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->selectRaw("
            max(WarehouseCode) WarehouseCode,
            count(ID) TotalPosted, 	
            SUM(CASE WHEN SapDocumentNumber is null THEN 1 ELSE 0 END) as TotalSapUnposted,
            SUM(CASE WHEN SapDocumentNumber is not null THEN 1 ELSE 0 END) as TotalSapPosted
        ")
        ->whereBetween('CreationDate', ['2024-01-01','2024-01-15'])
        ->groupBy('WarehouseCode')
        ->get();


        return response()->json($results);
    }   
}
