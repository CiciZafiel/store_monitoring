<?php 
ini_set("memory_limit",-1);
ini_set('max_execution_time', 0);
?>
<table>
    <thead>
        <tr>
            <th style="text-align: center; font-size: 12px; font-weight: bold;">ID</th>
            <th style="text-align: center; font-size: 12px; font-weight: bold;">Creation Date</th>
            <th style="text-align: center; font-size: 12px; font-weight: bold;">Sales Invoice</th>
            <th style="text-align: center; font-size: 12px; font-weight: bold;">WarehouseCode</th>
            <th style="text-align: center; font-size: 12px; font-weight: bold;">Sap Document Number</th>
            <th style="text-align: center; font-size: 12px; font-weight: bold;">Sap Incoming Document Number</th>
        </tr>
    </thead>
    <tbody>
    @foreach($sales_sap_unposted as $item)
        <tr>
            <td>{{ $item->ID }}</td>
            <td>{{ $item->CreationDate }}</td>
            <td>{{ $item->CustomerReferenceNumber }}</td>
            <td>{{ $item->WarehouseCode }}</td>
            <td>{{ $item->SapDocumentNumber }}</td>
            <td>{{ $item->SapIncomingDocumentNumber }}</td>            
        </tr>
    @endforeach

    

    </tbody>
</table>