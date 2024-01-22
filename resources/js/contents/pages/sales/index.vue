<template>
    <div>
        <div class="container-fluid pb-3">
            <div class="d-grid gap-3" style="grid-template-columns: 1fr 1fr 1fr 1fr;">
                <div class="bg-body-tertiary border rounded-3 card">
                    <div class="card">
                    <div class="card-header">
                        <h3>Total Unposted to SAP</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalUnpostedSAP}}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-body-tertiary border rounded-3 card">
                    <div class="card">
                    <div class="card-header">
                        <h3>SAP Posted Today</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalPostedSAP}}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-body-tertiary border rounded-3 card">
                    <div class="card">
                    <div class="card-header">
                        <h3>Server Posted Today</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalPostedServer}}</h2>
                        </div>
                    </div>
                </div>
                <div class="bg-body-tertiary border rounded-3 card">
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
                        <h2><center>Sales Details</center></h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-striped table-dark">
                            <thead>
                                <tr class="table-primary">
                                <th scope="col">ID</th>
                                <th scope="col">Creatation Date</th>
                                <th scope="col">Card Name</th>
                                <th scope="col">Warehouse Code</th>
                                <th scope="col">Comments</th>
                                <th scope="col">Sap Document Number</th>
                                <th scope="col">Sap Incoming Document Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>
                                <tr>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                    <td>TBD</td>
                                </tr>

                            </tbody>
                        </table>
            
                    </div>
                </div>
            </div>    
        </div>
    </div>
</template>


<script>
export default {
    async mounted(){
        this.totalUnpostedToSap('/api/total-unposted-to-sap');
        this.totalPostedToSAP('/api/total-posted-to-sap');
        this.totalPostedToServer('/api/total-posted-to-server');
        this.totalUnpostedToSAPToday('/api/total-unposted-to-sap-today');
    },

    data(){
        return{
            //sales_list: [],
            totalUnpostedSAP: '',
            totalPostedSAP: '',
            totalPostedServer: '',
            totalUnpostedSAPToday: '',
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

    },
}
</script>