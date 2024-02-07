<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\StoreMonitoring\PingLogs;

class DashboardController extends Controller
{

    public function getStoreList(Request $request)
    {  
        $query = DB::connection('serverDB')
        ->table('tbl_store')
        ->selectRaw("
            warehouse_code,
            store_name,
            store_ip,
            '3' as store_availability
        ")
        ->where('store_status','Active');
        
        if($request['searchThis'] != null){         
            
            $query->where(function($qry) use($request){
                $qry->orWhere('warehouse_code','like', '%' .$request['searchThis']. '%')
                    ->orWhere('store_name', 'like', '%'.$request['searchThis'].'%')
                    ->orWhere('store_ip', 'like', '%'.$request['searchThis'].'%');
            });
        }

        $results = $query->paginate(10);


        return response()->json($results);
    }



    public function pingIpAddress(Request $request) 
    { 
        // Define the ping command based on the operating system
        $ipAddress = $request->ip;
        $pingCommand = 'ping -c 4';

        // Run the ping command
        $command = escapeshellcmd("$pingCommand $ipAddress");
        exec($command, $output, $result);

        // Check the result (0 = successful)
        if ($result === 0) {
            $this->pingIpAddressLogs($request['warehouse_code'], 'Online');

            return response()->json(true);
        } else {
            $this->pingIpAddressLogs($request['warehouse_code'], 'Offline');

            return response()->json(false);
        }
    }

    public function pingIpAddressLogs($warehouse_code, $ping_status)
    {   
        $current_date = new Carbon();

        $result = PingLogs::insert([
            'Store' => $warehouse_code,
            'Description' => $ping_status,
            'CreationDate' => $current_date
        ]);
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
        $creation_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->whereNotNull('SapDocumentNumber')
        ->whereRaw('CreationDate = Cast(GETDATE()-1 as Date)')
        ->count();

        $posting_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->whereNotNull('SapDocumentNumber')
        ->whereRaw('PostingDate = Cast(GETDATE()-1 as Date)')
        ->count();       


        $results = $creation_date + $posting_date;
     
        return response()->json($results);
    }



    public function gettotalPostedToServerToday(Request $request)
    {
        

        $creation_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->whereRaw('CreationDate = Cast(GETDATE()-1 as Date)')
        ->count();

        $posting_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->whereRaw('PostingDate = Cast(GETDATE()-1 as Date)')
        ->count();       

        
        $results = $creation_date + $posting_date;

        return response()->json($results);
    }



    public function gettotalUnpostedToSAPToday(Request $request)
    {
        $creation_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->whereNull('SapDocumentNumber')
        ->whereRaw('CreationDate = Cast(GETDATE()-1 as Date)')
        ->count();

        $posting_date = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->whereNull('SapDocumentNumber')
        ->whereRaw('PostingDate = Cast(GETDATE()-1 as Date)')
        ->count();       


        $results = $creation_date + $posting_date;
        
        return response()->json($results);
    }
}
