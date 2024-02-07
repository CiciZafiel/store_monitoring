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
            Max(Warehouse) as Warehouse,
            Max(tbl_store.store_name) as store_name,
            Max(tbl_store.store_ip) as store_ip,
            Count(ItemCode) as ServerSerialQty,
            '0' as StoreSerialQty,
            '0' as ServerSerialAvailableCount,
            '0' as StoreSerialAvailableCount,
            '0' as SerialExistingCount
            
        ")
        ->join('tbl_store', 'tbl_store.warehouse_code', '=', 'tbl_WarehouseItemsSerials.Warehouse')
        ->groupBy('ItemCode');

        if($request['searchThis'] != null){         
            
            $query->where(function($qry) use($request){
                $qry->orWhere('ItemCode','like', '%' .$request['searchThis']. '%')
                    ->orWhere('Warehouse', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('IntrSerial', 'like', '%'.$request['searchThis'].'%');
            });
        }

        $results = $query->orderBy('ItemCode', 'ASC')
        ->paginate(10);

        
        return response()->json($results);
    }

    public function getSerialPerItem()
    {
        $query = DB::connect('serverDB')
        ->table('tbl_WarehouseItemsSerials')
        ->where('Warehouse', $request['warehouseCode'])
        ->selectRaw("
            ItemCode,
            Warehouse,
            tbl_store.store_name,
            tbl_store.store_ip,
            IntrSerial,
            SuppSerial,
            '0' as Exist
            
        ")
        ->join('tbl_store', 'tbl_store.warehouse_code', '=', 'tbl_WarehouseItemsSerials.Warehouse');
    }

    public function pingStore(Request $request)
    {  
        $db_con = [
            'host' => $request['ip_address']
        ];
        $this->setDynamicDatabaseConnection($db_con);
   
        try {
            DB::connection('storeDB')->getPdo();
            
        } catch (\Exception $e) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function checkSerialStatusPerItem(Request $request)
    {
        $db_con = [
            'host' => $request['ip_address']
        ];
        $this->setDynamicDatabaseConnection($db_con);

        $server_result = DB::connection('serverDB')
        ->table('tbl_WarehouseItemsSerials')
        ->selectRaw("ItemCode, IntrSerial, SuppSerial, SerialStatus as ServerSerialStatus,'0' as Exist") 
        ->where(function($qry) use($request){
            $qry->where('ItemCode', $request['item_code'])
            ->where('tbl_store.store_ip', $request['ip_address']);
        })
        ->join('tbl_store', 'tbl_store.warehouse_code', '=', 'tbl_WarehouseItemsSerials.Warehouse')
        ->orderBy('IntrSerial', 'ASC')
        ->get();      

        
        $store_result = DB::connection('storeDB')
        ->table('tbl_WarehouseItemsSerials')
        ->selectRaw("ItemCode,IntrSerial, SerialStatus as StoreSerialStatus") 
        ->where('ItemCode',$request['item_code'])
        ->orderBy('IntrSerial', 'ASC')
        ->get();

        $results = [$server_result, $store_result];
      
        
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

    public function serialTransactionHistory(Request $request)
    {
       

        $sales = DB::connection('serverDB')
        ->table('tbl_SalesSerialNumber')
        ->selectRaw(" Top 2
            tbl_SalesSerialNumber.ID as ID,
            'Sales' as TransType,
            tbl_SalesHeader.CreationDate 
        ")
        ->where(function($qry) use($request){
            $qry->where('tbl_SalesSerialNumber.ItemCode',$request['item_code'])
            ->where('tbl_SalesSerialNumber.SystemSerialNumber',$request['supp_serial'])
            ->where('tbl_SalesSerialNumber.InternalSerialNumber',$request['intr_serial']);
        })
        ->join('tbl_SalesHeader', 'tbl_SalesHeader.ID', '=', 'tbl_SalesSerialNumber.ID');


        $exchange = DB::connection('serverDB')
        ->table('tbl_ExchangeSerialNumber')
        ->selectRaw(" Top 2
            tbl_ExchangeSerialNumber.ID as ID,
            'Exchange' as TransType,
            tbl_ExchangeHeader.CreationDate
        ")
        ->where(function($qry) use($request){
            $qry->where('tbl_SalesSerialNumber.ItemCode',$request['item_code'])
            ->where('tbl_SalesSerialNumber.SystemSerialNumber',$request['supp_serial'])
            ->where('tbl_SalesSerialNumber.InternalSerialNumber',$request['intr_serial']);
        })
        ->join('tbl_ExchangeHeader', 'tbl_ExchangeHeader.ID', '=', 'tbl_ExchangeSerialNumber.ID');


        $stock_transfer_recieve = DB::connection('serverDB')
        ->table('tbl_StockTransferReceiveSerialNumber')
        ->selectRaw(" Top 2
            tbl_StockTransferReceiveSerialNumber.ID as ID,
            'Stock Transfer Recieve' as TransType,
            tbl_StockTransferReceiveHeader.CreationDate
        ")
        ->where(function($qry) use($request){
            $qry->where('tbl_SalesSerialNumber.ItemCode',$request['item_code'])
            ->where('tbl_SalesSerialNumber.SystemSerialNumber',$request['supp_serial'])
            ->where('tbl_SalesSerialNumber.InternalSerialNumber',$request['intr_serial']);
        })
        ->join('tbl_StockTransferReceiveHeader', 'tbl_StockTransferReceiveHeader.ID', '=', 'tbl_StockTransferReceiveSerialNumber.ID');


        $stock_transfer_shipment = DB::connection('serverDB')
        ->table('tbl_StockTransferShipmentSerialNumber')
        ->selectRaw(" Top 3
            tbl_StockTransferShipmentSerialNumber.ID as ID,
            'Stock Transfer Shipment' as TransType,
            tbl_StockTransferShipmentHeader.CreationDate
        ")
        ->where(function($qry) use($request){
            $qry->where('tbl_SalesSerialNumber.ItemCode',$request['item_code'])
            ->where('tbl_SalesSerialNumber.SystemSerialNumber',$request['supp_serial'])
            ->where('tbl_SalesSerialNumber.InternalSerialNumber',$request['intr_serial']);
        })
        ->join('tbl_StockTransferShipmentHeader', 'tbl_StockTransferShipmentHeader.ID', '=', 'tbl_StockTransferShipmentSerialNumber.ID');


        $transaction_history = $sales
        // ->unionAll($exchange)
        // ->unionAll($stock_transfer_recieve)
        // ->unionAll($stock_transfer_shipment)
        ->orderBy('CreationDate', 'DESC')
        ->get();


        return response()->json($transaction_history);

    }
}
