<template>
    <div>
        <div class="container-fluid pb-3">
            <div class="d-grid gap-3" style="grid-template-columns: 1fr 1fr 1fr 1fr;">
                <div class="bg-body-tertiary border rounded-3 card"
                    @click="subHeader= 'Unposted To Sap'; salesType= 'sap_unposted'; this.getSalesDetails(this.url.salesDetails)">
                    <div class="card">
                    <div class="card-header">
                        <h3>Total Unposted to SAP</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalUnpostedSAP}}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-body-tertiary border rounded-3 card"
                    @click="subHeader= 'SAP Posted Today'; salesType= 'sap_posted_today'; this.getSalesDetails(this.url.salesDetails)">
                    <div class="card">
                    <div class="card-header">
                        <h3>SAP Posted Yesterday</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalPostedSAP}}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-body-tertiary border rounded-3 card"
                    @click="subHeader= 'Server Posted Today'; salesType= 'server_unposted_today'; this.getSalesDetails(this.url.salesDetails)">
                    <div class="card">
                    <div class="card-header">
                        <h3>Server Posted Yesterday</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalPostedServer}}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-body-tertiary border rounded-3 card"
                    @click="subHeader= 'Sap Unposted Today'; salesType= 'sap_unposted_today'; this.getSalesDetails(this.url.salesDetails)" >
                    <div class="card">
                    <div class="card-header">
                        <h3>SAP Unposted Yesterday</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalUnpostedSAPToday}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-grid gap-3" style="grid-template-columns: 1fr;">


                <div class="card">
                    <div class="card-header">
                        <!-- <h2><center>Sales Details <span>Total Unposted Sap</span></center></h2> -->
                        <!-- <center><h2>Sales Details</h2></center> -->
                        <div class="d-flex justify-content-center">
                            <h2>Store Sales Posted and Unposted</h2>
                            <!-- <h4 class="pt-1">&nbsp;&nbsp; ({{ subHeader }})</h4> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-3">
                                <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                    v-model="searchThis"
                                    @keyup="search()">
                            </div>
                            <div class="col-3">
                                <div class="d-flex align-items-center">
                                    <label for="" class="me-2">From: </label>
                                    <input type="date" class="form-control" placeholder="Search..." aria-label="Search"
                                        v-model="dateFrom" @change="getSalesPostedAndUnpostedSummary(url.salesPostedAndUnpostedSummary);">
                                </div>
                                
                            </div>

                            <div class="col-3">
                                <div class="d-flex align-items-center">
                                    <label for="" class="me-2">To: </label>
                                    <input type="date" class="form-control" placeholder="Search..." aria-label="Search"
                                        v-model="dateTo" @change="getSalesPostedAndUnpostedSummary(url.salesPostedAndUnpostedSummary);">
                                </div>
                                
                            </div>
                            <!-- <div class="col-auto">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="filter" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <div class="dropdown-menu" style="width: 40em" aria-labelledby="filter">
                                        <form class="px-4 py-3">
                                            <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">Email address</label>
                                            <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Sign in</button>
                                        </form>   
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="pt-4">
                            <table class="table table-hover table-striped table-dark" style="height: 10em;">
                                <thead>
                                    <tr class="table-primary">
                                    <th scope="col">Store Name</th>
                                    <th scope="col">Total Posted</th>
                                    <th scope="col">Total Sap Posted</th>
                                    <th scope="col">Total Sap Unposted</th>
                                    <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- No data -->
                                    <!-- && !loader.data -->
                                    <tr v-if="(salesPostedAndUnpostedSummary.total <= 0 && !tblLoader )">
                                        <td class="align-middle text-center" colspan="5">
                                            <span>No data available in table</span>
                                        </td>
                                    </tr>
                                    <tr v-if="tblLoader">
                                        <td class="align-middle" colspan="5">
                                            <div class="d-flex justify-content-center" >
                                                <div class="spinner-border" role="status">
                                                </div>
                                                <div class="d-flex align-items-center px-2">
                                                    <span class="">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-else v-for="item, index in salesPostedAndUnpostedSummary.data" :key="index">
                                        <td>{{ item.WarehouseName }}</td>
                                        <td>{{ item.TotalPosted }}</td>
                                        <td>{{ item.TotalSapPosted  }}</td>
                                        <td>{{ item.TotalSapUnposted }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" 
                                            @click="exportWarehouseCode=item.WarehouseCode;storeSalesExportExcel(this.url.storeSalesExportExcel)"><i class="fas fa-download"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <!-- 
                        |==========================================================================
                        | Pagination 
                        |==========================================================================
                        -->           
                        <div  class="d-flex justify-content-center mb-2">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <button type="button" class="btn btn-secondary"
                                    @click="url.salesPostedAndUnpostedSummaryCurrentPage=salesPostedAndUnpostedSummary.prev_page_url;getSalesPostedAndUnpostedSummary(salesPostedAndUnpostedSummary.prev_page_url);" 
                                    :disabled="salesPostedAndUnpostedSummary.prev_page_url===null">Previous
                                </button>
                                <div class="btn-group" role="group">
                                    <select class="px-2" @change="jumPage($event)">
                                        <option v-for="(pageNumber, pi) in salesPostedAndUnpostedSummary.last_page" 
                                            :key="pi"
                                            :selected="pageNumber == salesPostedAndUnpostedSummary.current_page">{{pageNumber}}
                                        </option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-secondary"
                                    @click="url.salesPostedAndUnpostedSummaryCurrentPage=salesPostedAndUnpostedSummary.next_page_url;getSalesPostedAndUnpostedSummary(salesPostedAndUnpostedSummary.next_page_url);" 
                                    :disabled="salesPostedAndUnpostedSummary.next_page_url===null">Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>    
        </div>
    </div>
</template>


<script>
import moment from "moment";

export default {
    async mounted(){
        this.totalUnpostedToSap(this.url.totalSapUnposted);
        this.totalPostedToSAP(this.url.totalSapPostedToday);
        this.totalPostedToServer(this.url.totalServerPostedToday);
        this.totalUnpostedToSAPToday(this.url.totalSapUnpostedToday);
        this.getSalesDetails(this.url.salesDetails);
        this.getSalesPostedAndUnpostedSummary(this.url.salesPostedAndUnpostedSummary);

        
        setInterval(() => {
            this.totalUnpostedToSap(this.url.totalSapUnposted);
            this.totalPostedToSAP(this.url.totalSapPostedToday);
            this.totalPostedToServer(this.url.totalServerPostedToday);
            this.totalUnpostedToSAPToday(this.url.totalSapUnpostedToday);
            this.getSalesDetails(this.url.salesDetails);
            this.getSalesPostedAndUnpostedSummary(this.url.salesPostedAndUnpostedSummaryCurrentPage);
        }, 60000);
    },

    data(){
        return{
            moment: moment,
            sales: [],
            salesPostedAndUnpostedSummary: [],
            subHeader: 'Unposted To Sap',
            totalUnpostedSAP: '',
            totalPostedSAP: '',
            totalPostedServer: '',
            totalUnpostedSAPToday: '',
            salesType: 'server_unposted_today',
            dateFrom: moment(new Date()).subtract(7, 'days').format("YYYY-MM-DD"),
            dateTo: moment(new Date()).format("YYYY-MM-DD"),
            exportWarehouseCode: '',

            url: {
                totalSapUnposted: '/api/total-unposted-to-sap',
                totalSapPostedToday: '/api/total-posted-to-sap',
                totalServerPostedToday:'/api/total-posted-to-server',
                totalSapUnpostedToday: '/api/total-unposted-to-sap-today',
                salesDetails: '/api/sales-details',
                salesPostedAndUnpostedSummary: '/api/sales-posted-and-unposted-summary',
                salesPostedAndUnpostedSummaryCurrentPage: '/api/sales-posted-and-unposted-summary',
                storeSalesExportExcel: 'api/store-sales/export-excel',
            },

            count: 15, 
            searchThis: '',
            tblLoader: false,
        }
    },
    methods:{
        totalUnpostedToSap(URL){
            axios.get(URL).then(response=>{
               this.totalUnpostedSAP = response.data
            })
        },
        totalPostedToSAP(URL){
            axios.get(URL).then(response=>{
               this.totalPostedSAP = response.data
            })
        },
        totalPostedToServer(URL){
            axios.get(URL).then(response=>{
               this.totalPostedServer = response.data
                
            })
        },
        totalUnpostedToSAPToday(URL){
            axios.get(URL).then(response=>{
               this.totalUnpostedSAPToday = response.data
            })
        },

        getSalesDetails(URL){            
            // this.tblLoader = true;

            // axios.post(URL,{
            //     type: this.salesType,
            //     searchThis : this.searchThis,
            // }).then(response=>{
            //     this.sales = response.data;
            // }).finally(()=>{
            //     this.tblLoader = false;
            // });
        },    

        getSalesPostedAndUnpostedSummary(URL){
            this.tblLoader = true;
            axios.post(URL,{
                searchThis: this.searchThis,
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
            }).then(response=>{
                this.salesPostedAndUnpostedSummary = response.data;

                this.tblLoader = false;
            })
        },

        storeSalesExportExcel(URL){
            axios.post(URL,{
                warehouseCode: this.exportWarehouseCode,
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
            },{ responseType: 'blob' }).then(response=>{
                var file= window.URL.createObjectURL(new Blob([response.data]));
                var aTag = document.createElement('a');
                aTag.href = file;
                aTag.setAttribute('download', `Sales_Posted_And_Unposted_${this.dateFrom}-${this.dateTo}.xlsx`);
                document.body.appendChild(aTag);
                aTag.click();
            })
        },

        search(){
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.getSalesPostedAndUnpostedSummary(this.url.salesPostedAndUnpostedSummary);       
            }, 300);
        },

        jumPage(_value){            
            var url = `/api/sales-posted-and-unposted-summary?page=${_value.target.value}`;
            this.url.salesPostedAndUnpostedSummaryCurrentPage = url;
            this.getSalesPostedAndUnpostedSummary(url);
        },

        
    },
}
</script>