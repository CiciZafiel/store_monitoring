<?php
namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


trait ConfigDatabaseTrait
{
    public function setDynamicDatabaseConnection($db_con)
    {
        foreach ($db_con as $key => $value) {
            Config::set('database.connections.storeDB.'.$key, $db_con);
            
        }

        DB::purge('storeDB');
        DB::reconnect();
    }
}
?>