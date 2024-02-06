<template>
    <content_loader v-if="content_loader"></content_loader>
    <div v-else class="container-fluid pb-3">
        <div class="d-grid gap-3" style="grid-template-columns: 1fr;">
                <div class="card">
                    <div class="card-header">
                        <!-- <h2><center>Sales Details <span>Total Unposted Sap</span></center></h2> -->
                        <!-- <center><h2>Sales Details</h2></center> -->
                        <div class="d-flex justify-content-center">
                            <h2>Inventory</h2>
                            <!-- <h4 class="pt-1">&nbsp;&nbsp; ({{ subHeader }})</h4> -->
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="mb-4">
                                <div class="row">
                                    <div class="form-group row col-sm-6">
                                        <label for="colFormLabelSm" class="col-sm-auto col-form-label col-form-label-sm fs-6 fw-medium d-flex align-items-center">Store</label>
                                        <div class="col-sm-10">
                                            <select  @change="storeDPOnChange($event) " class="form-select" data-te-toggle="tooltip"  
                                            date-te-placement="bottom"                                         
                                            :title="filter.store">
                                                <option value=""></option>
                                                <option v-for="store,index in stores"
                                                :data-warehouse_code="749"
                                                :value="store.warehouse_code+'*'+store.store_ip"
                                                :key="index">{{ store.store_name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <table class="table table-hover table-striped table-dark" style="height: 10em;">
                            <thead>
                                <tr class="table-primary">
                                <th scope="col">Item Code</th>
                                <th scope="col">Description</th>
                                <th scope="col">Server Qty</th>
                                <th scope="col">Store Qty</th>
                                <!-- <th scope="col">Status</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- No data -->
                                <!-- && !loader.data -->
                                <tr v-if="(items.total <= 0 && !tblLoader )">
                                    <td class="align-middle text-center" colspan="8">
                                        <span>No data available in table</span>
                                    </td>
                                </tr>

                                <tr v-if="tblLoader">
                                    <td class="align-middle" colspan="8">
                                        <div class="d-flex justify-content-center" >   
                                            <div class="spinner-border" role="status">
                                            </div>
                                            <div class="d-flex align-items-center px-2">
                                                <span class="">Loading...</span>
                                            </div>        
                                        </div>
                                    </td>
                                </tr>

                                <tr v-else v-for="item,index in items.data" :key="index">
                                    <td>{{ item.ItemCode }}</td>
                                    <td>{{ item.ItemName }}</td>
                                    <td>{{ Number(item.ServerQty) }}</td>
                                    <td>{{ Number(item.StoreQty) }}</td>
                                    <!-- <td></td> -->
                                </tr>

                            </tbody>
                        </table>


                        <!-- 
                        |==========================================================================
                        | Pagination 
                        |==========================================================================
                        -->           
                        <div v-if="items.total > items.per_page" class="d-flex justify-content-center mb-2">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <button type="button" class="btn btn-secondary"
                                    @click="getItems(items.prev_page_url);" 
                                    :disabled="items.prev_page_url===null">Previous
                                </button>
                                <div class="btn-group" role="group">
                                    <select class="px-2" @change="jumPage($event)">
                                        <option v-for="(pageNumber, pi) in items.last_page" 
                                            :key="pi"
                                            :selected="pageNumber == items.current_page">{{pageNumber}}
                                        </option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-secondary"
                                    @click="getItems(items.next_page_url);" 
                                    :disabled="items.next_page_url===null">Next
                                </button>
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
        this.getStores(this.url.stores);
        // await this.getItems(this.url.items);
        this.content_loader = false;
    },

    data(){
        return{
            content_loader : true,
            items: { 
                total: 0,
            },
            stores: {},
            filter: {
                store: {
                    ip_address: '',
                    warehouse_code: '',
                },
                item_code: '',
            },

            url : {
                items: '/api/inventory/items',
                stores: '/api/invetory/store-lists',
                store_item_qty: '/api/inventory/store-item-qty',
                ping_store: '/api/inventory/ping-store',
            },
            tblLoader: false,
        }
    },

    methods:{
        async getItems(URL){
            this.tblLoader = true;

            axios.post(URL,{
                filter : this.filter,
            }).then(async (response)=>{
                this.items = response.data;


                if(await this.pingStore()){
                    if(this.filter.store.warehouse_code){
                        for (let index = 0; index < this.items.data.length; index++) {   
                            this.filter.item_code = this.items.data[index].ItemCode;
                            this.getStoresItemQty(index);
                        }
                    }
                }else{
                    this.$swal({
                        icon: 'error',
                        title: 'Error found',
                        text: 'Could not connect to the store\'s database! Please try again later.',
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
               
            }).finally(()=>{
                this.tblLoader = false;
            })
        },

        getStores(URL){
            axios.get(URL).then(response=>{
                this.stores = response.data;
            })
        },

        getStoresItemQty(Index){

            axios.post(this.url.store_item_qty,{
                filter: this.filter,
            }).then(response=>{
                this.items['data'][Index]['StoreQty'] = response.data.StoreQty
            })
        },

        async pingStore(){
            const response =  await axios.post(this.url.ping_store,{
                filter: this.filter,
            })

            return response.data
        },

        storeDPOnChange(EVT){
            var slctd_store = EVT.target.value.split("*")
            this.filter.store.warehouse_code = slctd_store[0];
            this.filter.store.ip_address = slctd_store[1];
            
            this.getItems(this.url.items);
        },
        
        jumPage(_value){      
            var url = this.url.items + `?page=${_value.target.value}`;
            this.getItems(url);
        },
    }
}
</script>