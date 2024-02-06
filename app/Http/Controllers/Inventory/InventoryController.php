<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Traits\ConfigDatabaseTrait;

class InventoryController extends Controller
{
    use ConfigDatabaseTrait;

    public function getItems(Request $request)
    {  
        $query = DB::connection('serverDB')
        ->table('tbl_Items')        
        ->join('tbl_WarehouseItems', 'tbl_WarehouseItems.ItemCode', '=', 'tbl_Items.ItemCode')
        ->select(
            'tbl_Items.ItemCode',
            'tbl_Items.ItemName',
            'tbl_WarehouseItems.OnHand as ServerQty',
            DB::raw('0 as StoreQty')
        )
        ->where('tbl_WarehouseItems.WarehouseCode', $request['filter']['store']['warehouse_code'])
        ->orderBy('tbl_Items.ItemCode', 'ASC');
        

        $results = $query->paginate(10);
        
        return response()->json($results);
    }

    public function pingStore(Request $request)
    {  
        $db_con = [
            'host' => $request['filter']['store']['ip_address']
        ];
        $this->setDynamicDatabaseConnection($db_con);

        // $ipAddress = $request['filter']['store']['ip_address'];
        // $pingCommand = 'ping -c 3';

        // // Run the ping command
        // $command = escapeshellcmd("$pingCommand $ipAddress");
        // exec($command, $output, $result);

        // if ($result === 0) {
        //     return response()->json(true);
        // } else {
        //     return response()->json(false);
        // }
   
        try {
            DB::connection('storeDB')->getPdo();
            
        } catch (\Exception $e) {
            return response()->json(false);
        }

        return response()->json(true);
    }

    public function getStoresItemQty(Request $request)
    {   
        $db_con = [
            'host' => $request['filter']['store']['ip_address']
        ];
        $this->setDynamicDatabaseConnection($db_con);


        
        $results = DB::connection('storeDB')
        ->table('tbl_Items')        
        ->join('tbl_WarehouseItems', 'tbl_WarehouseItems.ItemCode', '=', 'tbl_Items.ItemCode')
        ->select(
            'tbl_WarehouseItems.OnHand as StoreQty'
        )
        ->where('tbl_WarehouseItems.WarehouseCode', $request['filter']['store']['warehouse_code'])
        ->where('tbl_WarehouseItems.ItemCode', $request['filter']['item_code'])
        ->orderBy('tbl_Items.ItemCode', 'ASC')
        ->first();

        return response()->json($results);

    }

    public function getStoreList()
    {
        $results = DB::connection('serverDB')
        ->table('tbl_store')
        ->select(
            'warehouse_code',
            'store_name',
            'store_ip'
        )
        ->get();
     
        return response()->json($results);
    }
}
