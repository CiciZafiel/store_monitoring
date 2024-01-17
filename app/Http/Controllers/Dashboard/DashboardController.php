<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(){
        return view('dashboard.index');
    }



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
            // return "Ping to $ipAddress was successful.";
            return response()->json(true);
        } else {
            // return "Ping to $ipAddress failed.";
            return response()->json(false);
        }
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
        ->select('SapDocumentNumber')
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
}
