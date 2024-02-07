<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;



class SalesExport implements WithMultipleSheets
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

    public function sheets(): array
    {
       
        return [
            new SalesSapPostedSheets($this->warehouse_code, $this->date_from, $this->date_to),
            new SalesSapUnpostedSheets($this->warehouse_code, $this->date_from, $this->date_to)
        ];
    }

   

    
}
