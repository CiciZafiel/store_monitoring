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
                        <h3>SAP Posted Today</h3>
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
                        <h3>Server Posted Today</h3>
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
                        <h3>SAP Unposted Today</h3>
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
                            <h2>Sales Posted and Unposted Summary</h2>
                            <!-- <h4 class="pt-1">&nbsp;&nbsp; ({{ subHeader }})</h4> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-end mt-2">
                            <div class="col-3">
                                <form class="w-100 me-3" role="search">
                                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                        v-model="searchThis"
                                        @keyup="search()">
                                </form>
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
                                    <th scope="col">StoreName</th>
                                    <th scope="col">Total Posted</th>
                                    <th scope="col">Total Sap Posted</th>
                                    <th scope="col">Total Sap Unposted</th>
                                    <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- No data -->
                                    <!-- && !loader.data -->
                                    <!-- <tr v-if="(sales.total <= 0 && !tblLoader )">
                                        <td class="align-middle text-center" colspan="9">
                                            <span>No data available in table</span>
                                        </td>
                                    </tr>
                                    <tr v-if="tblLoader">
                                        <td class="align-middle" colspan="9">
                                            <div class="d-flex justify-content-center" >
                                                <div class="spinner-border" role="status">
                                                </div>
                                                <div class="d-flex align-items-center px-2">
                                                    <span class="">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr> -->
                                    <tr v-for="item, index in salesPostedAndUnpostedSummary" :key="index">
                                        <td>{{ item.WarehouseCode }}</td>
                                        <td>{{ item.TotalPosted }}</td>
                                        <td>{{ item.TotalSapPosted  }}</td>
                                        <td>{{ item.TotalSapUnposted }}</td>
                                        <td><button type="button" class="btn btn-success"><i class="fas fa-download"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <!-- 
                        |==========================================================================
                        | Pagination 
                        |==========================================================================
                        -->           
                        <!-- <div  class="d-flex justify-content-center mb-2">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <button type="button" class="btn btn-secondary"
                                    @click="getSalesDetails(sales.prev_page_url);" 
                                    :disabled="sales.prev_page_url===null">Previous
                                </button>
                                <div class="btn-group" role="group">
                                    <select class="px-2" @change="jumPage($event)">
                                        <option v-for="(pageNumber, pi) in sales.last_page" 
                                            :key="pi"
                                            :selected="pageNumber == sales.current_page">{{pageNumber}}
                                        </option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-secondary"
                                    @click="getSalesDetails(sales.next_page_url);" 
                                    :disabled="sales.next_page_url===null">Next
                                </button>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <!-- <h2><center>Sales Details <span>Total Unposted Sap</span></center></h2> -->
                        <!-- <center><h2>Sales Details</h2></center> -->
                        <div class="d-flex justify-content-center">
                            <h2>Sales Details</h2>
                            <h4 class="pt-1">&nbsp;&nbsp; ({{ subHeader }})</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-end mt-2">
                            <div class="col-3">
                                <form class="w-100 me-3" role="search">
                                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                        v-model="searchThis"
                                        @keyup="search()">
                                </form>
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
                                    <th scope="col">ID</th>
                                    <th scope="col">Creatation Date</th>
                                    <th scope="col">SI</th>
                                    <th scope="col">Card Name</th>
                                    <th scope="col">Warehouse Code</th>
                                    <th scope="col">Comments</th>
                                    <th scope="col">Sap Document No</th>
                                    <th scope="col">Sap Incoming Document No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- No data -->
                                    <!-- && !loader.data -->
                                    <tr v-if="(sales.total <= 0 && !tblLoader )">
                                        <td class="align-middle text-center" colspan="9">
                                            <span>No data available in table</span>
                                        </td>
                                    </tr>
                                    <tr v-if="tblLoader">
                                        <td class="align-middle" colspan="9">
                                            <div class="d-flex justify-content-center" >
                                                <div class="spinner-border" role="status">
                                                </div>
                                                <div class="d-flex align-items-center px-2">
                                                    <span class="">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-else v-for="item, index in sales.data" :key="index">
                                        <td>{{ item.ID  }}</td>
                                        <td>{{ item.CreationDate }}</td>
                                        <td>{{ item.CustomerReferenceNumber }}</td>
                                        <td>{{ item.CardName }}</td>
                                        <td>{{ item.WarehouseCode }}</td>
                                        <td>{{ item.Comments }}</td>
                                        <td>{{ item.SapDocumentNumber }}</td>
                                        <td>{{ item.SapIncomingDocumentNumber }}</td>
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
                                    @click="getSalesDetails(sales.prev_page_url);" 
                                    :disabled="sales.prev_page_url===null">Previous
                                </button>
                                <div class="btn-group" role="group">
                                    <select class="px-2" @change="jumPage($event)">
                                        <option v-for="(pageNumber, pi) in sales.last_page" 
                                            :key="pi"
                                            :selected="pageNumber == sales.current_page">{{pageNumber}}
                                        </option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-secondary"
                                    @click="getSalesDetails(sales.next_page_url);" 
                                    :disabled="sales.next_page_url===null">Next
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
export default {
    async mounted(){
        this.totalUnpostedToSap(this.url.totalSapUnposted);
        this.totalPostedToSAP(this.url.totalSapPostedToday);
        this.totalPostedToServer(this.url.totalServerPostedToday);
        this.totalUnpostedToSAPToday(this.url.totalSapUnpostedToday);
        this.getSalesDetails(this.url.salesDetails);
        this.getSalesPostedAndUnpostedSummary(this.url.salesPostedAndUnpostedSummary);
    },

    data(){
        return{
            sales: [],
            salesPostedAndUnpostedSummary: [],
            subHeader: 'Unposted To Sap',
            totalUnpostedSAP: '',
            totalPostedSAP: '',
            totalPostedServer: '',
            totalUnpostedSAPToday: '',
            salesType: 'server_unposted_today',

            url: {
                totalSapUnposted: '/api/total-unposted-to-sap',
                totalSapPostedToday: '/api/total-posted-to-sap',
                totalServerPostedToday:'/api/total-posted-to-server',
                totalSapUnpostedToday: '/api/total-unposted-to-sap-today',
                salesDetails: '/api/sales-details',
                salesPostedAndUnpostedSummary: '/api/sales-posted-and-unposted-summary',
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
            this.tblLoader = true;

            axios.post(URL,{
                type: this.salesType,
                searchThis : this.searchThis,
            }).then(response=>{
                this.sales = response.data;
            }).finally(()=>{
                this.tblLoader = false;
            });
        },    

        getSalesPostedAndUnpostedSummary(URL){
            axios.post(URL).then(response=>{
                this.salesPostedAndUnpostedSummary = response.data;
            })
        },

        search(){
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.getSalesDetails(this.url.salesDetails);       
            }, 300);
        },

        jumPage(_value){            
            var url = `/api/sales-details?page=${_value.target.value}`;
            this.getSalesDetails(url);
        },

        
    },
}
</script>