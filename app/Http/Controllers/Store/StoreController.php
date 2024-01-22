<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Traits\ConfigDatabaseTrait;


class StoreController extends Controller
{
    use ConfigDatabaseTrait;

    public function getIMEIList(Request $request)
    {
        $query = DB::connection('serverDB')
        ->table('tbl_WarehouseItemsSerials')
        ->where('Warehouse', $request['warehouseCode'])
        ->selectRaw("
            ItemCode,
            Warehouse,
            tbl_store.store_name,
            tbl_store.store_ip,
            IntrSerial,
            SerialStatus,
            '0' as Exist
            
        ")
        ->join('tbl_store', 'tbl_store.warehouse_code', '=', 'tbl_WarehouseItemsSerials.Warehouse');
        
        if($request['searchThis'] != null){         
            
            $query->where(function($qry) use($request){
                $qry->orWhere('ItemCode','like', '%' .$request['searchThis']. '%')
                    ->orWhere('Warehouse', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('IntrSerial', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('SerialStatus', 'like', '%'.$request['searchThis'].'%');
            });
        }

        $results = $query->paginate(10);


        return response()->json($results);
    }

    public function searchIMEI(Request $request)
    {   
        $db_con = [
            'host' => $request['ip_address'],
        ];
        $this->setDynamicDatabaseConnection($db_con);
       
        $results = DB::connection('storeDB')
        ->table('tbl_WarehouseItemsSerials')
        ->where('IntrSerial', $request['imei'])
        ->get();

        if($results){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }
}
