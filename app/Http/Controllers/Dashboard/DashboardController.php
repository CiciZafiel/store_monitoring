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



    public function getStoreList()
    {
        $stores = DB::connection('serverDB')
        ->table('tbl_store')
        ->selectRaw("
            warehouse_code,
            store_name,
            store_ip,
            '3' as store_availability
        ")
        ->where('store_status','Active')
        ->take(10)
        ->get();

        return response()->json($stores);
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
}
