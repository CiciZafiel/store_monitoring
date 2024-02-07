<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

use Illuminate\Support\Facades\DB;

class SalesSapUnpostedSheets implements FromView,WithTitle
{
    public $warehouse_code;
    public $date_from;
    public $date_to;

    public function __construct($warehouse_code, $date_from, $date_to)
	{
	   $this->warehouse_code = $warehouse_code;
       $this->date_from = $date_from;
       $this->date_to = $date_to;
	}

    public function title(): string
    {
        return 'Sales Sap Unposted';
    }

    public function view(): View
    {

        $results = DB::connection('serverDB')
        ->table('tbl_SalesHeader')
        ->selectRaw("
            ID,
            CreationDate,
            CustomerReferenceNumber,
            WarehouseCode,
            SapDocumentNumber,
            SapIncomingDocumentNumber
        ")
        ->whereBetween('CreationDate', [$this->date_from,$this->date_to ])
        ->where('WarehouseCode',$this->warehouse_code)
        ->whereNull('SapDocumentNumber')
        ->get();
       
        return view('reports.sales.excels.sales-sap-unposted', ['sales_sap_unposted' => $results]);
    }
}
