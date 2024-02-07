<template>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModal">Serial Transaction</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <!-- <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" :class="slctd_tab == 'server'? 'active' : '' " aria-current="page" @click="slctd_tab = 'server'">Server</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="slctd_tab == 'store'? 'active' : ''" @click="slctd_tab = 'store'">Store</a>
                </li>
            </ul> -->

            <div class="border-start border-end border-bottom p-2">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="(transaction_history.length <= 0 && !tblLoader )">
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
                            <tr v-for="item,index in transaction_history">
                                <td>{{ item.ID }}</td>
                                <td>{{ item.TransType }}</td>
                                <td>{{ item.CreationDate     }}</td>    
                            </tr>      
                        </tbody>
                    </table>
                </div>
            </div>


            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
        </div>
    </div>
    </div>
</template>

<script>
export default{
    props:{
        warehouse_code : 'string',
        item_code : 'string',
        intr_serial : 'string',
        supp_serial : 'string'
    },

    data(){
        return{
            transaction_history : "",
            slctd_tab : 'server',

            url: {
                serial_transaction_history: '/api/store/serial-transaction-history',
            },

            tblLoader: false,
        }
    },

    methods: {
        getSerialTransactionHistory(WarehouseCode, ItemCode, IntrSerial, SuppSerial){
            this.tblLoader = true;
            axios.post(this.url.serial_transaction_history,{
                warehouse_code: WarehouseCode,
                item_code: ItemCode,
                intr_serial: IntrSerial,
                supp_serial: SuppSerial, 
            }).then(response=>{
                this.transaction_history = response.data;
                this.tblLoader = false;
            })
        }
    },
}
</script>