<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Excel;
use App\Exports\SalesExport;

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

    public function getSalesPostedAndUnpostedSummary(Request $request)
    {
        $query_creation_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->selectRaw("
            max(tbl_warehouse.WarehouseCode) WarehouseCode,
            max(tbl_warehouse.WarehouseName) as WarehouseName,
            count(ID) TotalPosted, 	
            SUM(CASE WHEN SapDocumentNumber is null THEN 1 ELSE 0 END) as TotalSapUnposted,
            SUM(CASE WHEN SapDocumentNumber is not null THEN 1 ELSE 0 END) as TotalSapPosted,
            (
                select
                    SUM(CASE WHEN Serialized = 'Y' THEN 1 ELSE 0 END) as Serialized
                from tbl_SalesHeader 
                inner join tbl_SalesDetails on tbl_SalesDetails.ID = tbl_SalesHeader.ID
                where CreationDate Between '".$request['dateFrom']."' and '".$request['dateTo']."'  and tbl_SalesDetails.WhsCode =  max(tbl_warehouse.WarehouseCode)
            ) as Serialized,
            (
                select
                    SUM(CASE WHEN Serialized = 'N' THEN 1 ELSE 0 END) as NonSerialized
                from tbl_SalesHeader 
                inner join tbl_SalesDetails on tbl_SalesDetails.ID = tbl_SalesHeader.ID
                where CreationDate Between '".$request['dateFrom']."' and '".$request['dateTo']."'  and tbl_SalesDetails.WhsCode =  max(tbl_warehouse.WarehouseCode)
            ) as NonSerialized
        ")
        ->whereBetween('CreationDate', [$request['dateFrom'],$request['dateTo']])
        ->join('tbl_warehouse','tbl_warehouse.WarehouseCode', '=', 'tbl_SalesHeader.WarehouseCode')
        ->groupBy('tbl_warehouse.WarehouseCode');

        $query_posting_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->selectRaw("
            max(tbl_warehouse.WarehouseCode) WarehouseCode,
            max(tbl_warehouse.WarehouseName) as WarehouseName,
            count(ID) TotalPosted, 	
            SUM(CASE WHEN SapDocumentNumber is null THEN 1 ELSE 0 END) as TotalSapUnposted,
            SUM(CASE WHEN SapDocumentNumber is not null THEN 1 ELSE 0 END) as TotalSapPosted,
            (
                select
                    SUM(CASE WHEN Serialized = 'Y' THEN 1 ELSE 0 END) as Serialized
                from tbl_SalesHeader 
                inner join tbl_SalesDetails on tbl_SalesDetails.ID = tbl_SalesHeader.ID
                where PostingDate Between '".$request['dateFrom']."' and '".$request['dateTo']."'  and tbl_SalesDetails.WhsCode =  max(tbl_warehouse.WarehouseCode)
            ) as Serialized,
            (
                select
                    SUM(CASE WHEN Serialized = 'N' THEN 1 ELSE 0 END) as NonSerialized
                from tbl_SalesHeader 
                inner join tbl_SalesDetails on tbl_SalesDetails.ID = tbl_SalesHeader.ID
                where PostingDate Between '".$request['dateFrom']."' and '".$request['dateTo']."'  and tbl_SalesDetails.WhsCode =  max(tbl_warehouse.WarehouseCode)
            ) as NonSerialized
        ")
        ->whereBetween('PostingDate', [$request['dateFrom'],$request['dateTo']])
        ->join('tbl_warehouse','tbl_warehouse.WarehouseCode', '=', 'tbl_SalesHeader.WarehouseCode')
        ->groupBy('tbl_warehouse.WarehouseCode');


 


        // if($request['searchThis'] != null){                   
        //     $query->where(function($qry) use($request){
        //         $qry->orWhere('tbl_warehouse.WarehouseCode', 'like', '%'.$request['searchThis'].'%')  
        //         ->orWhere('tbl_warehouse.WarehouseName', 'like', '%'.$request['searchThis'].'%');               
        //     });      
        // }




        $results = $query_creation_date
        ->paginate(10);

        
        return response()->json($results);
    }   

    public function ExportSalesExcel(Request $request)
    {               
        return Excel::download(new SalesExport($request['warehouseCode'],$request['dateFrom'],$request['dateTo']), 'SalesPostedAndUnposted.xlsx');
    }
}
