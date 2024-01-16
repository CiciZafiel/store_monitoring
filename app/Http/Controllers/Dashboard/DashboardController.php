<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.index');
    }

    function pingIpAddress() {
        // Define the ping command based on the operating system
        $ipAddress = '192.168.12.251';
        $pingCommand = 'ping -c 4';

        // Run the ping command
        $command = escapeshellcmd("$pingCommand $ipAddress");
        exec($command, $output, $result);

        // Check the result
        if ($result === 0) {
            return "Ping to $ipAddress was successful.";
        } else {
            return "Ping to $ipAddress failed.";
        }
    }
}
