<?php

namespace App\Models\StoreMonitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PingLogs extends Model
{
    use HasFactory;

    protected $connection = 'storeMonitoringDB';

    protected $table = 'tbl_logs';

    protected $primaryKey = 'Log_ID';

    protected $fillable = [
        'Store',
        'Description',
        'CreationDate',
    ];

    public $timestamps = false;
}
