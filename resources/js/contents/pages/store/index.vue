<template>
    <content_loader v-if="content_loader"></content_loader>
    <div v-else>
        <div class="container-fluid pb-3">

            <div class="d-grid gap-3" style="grid-template-columns: 1fr;">
                <div class="row">
                    <div class="col"><h2>{{ imei_lists.data[0].store_name}}</h2></div>
                    <div class="col-3">
                        <div class="card ">
                            <div class="card-header border-bottom-0">
                                <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                v-model="searchThis"
                                @keyup="search()">
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="row">
                            <div class="col-5">
                               <div class="card">
                                    <div class="card-header"><h4>Summary</h4></div>
                                    <div class="card-body">
                                        <div class="row justify-content-end mt-2">
                                            <div class="col-3">
                                                <!-- <form class="w-100 me-3" role="search">
                                                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                                        v-model="searchThis"
                                                        @keyup="search()">
                                                </form> -->
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped table-dark">
                                                <thead>
                                                    <tr class="table-primary align-middle">
                                                        <th style="width: 34%;">Item Code</th>
                                                        <th style="width: 25%;">Serial Availability (Server)</th>
                                                        <th style="width: 25%;">Serial Availability (Store)</th>
                                                        <!-- <th style="width: 16%;">Exist</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="item, index in imei_lists.data" :key="index" @click="slctd_item=item.ItemCode;">
                                                        <td>{{ item.ItemCode }}</td>
                                                        <td>{{ item.ServerSerialAvailableCount +' / '+ item.ServerSerialQty }}</td>
                                                        <td>{{ item.StoreSerialAvailableCount +' / '+ item.StoreSerialQty }}</td>
                                                        <!-- <td>{{ item.SerialExistingCount +' / '+ item.StoreSerialQty }}</td> -->
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
                                                    @click="getIMEIList(imei_lists.prev_page_url);" 
                                                    :disabled="imei_lists.prev_page_url===null">Previous
                                                </button>
                                                <div class="btn-group" role="group">
                                                    <select class="px-2" @change="jumPage($event)">
                                                        <option v-for="(pageNumber, pi) in imei_lists.last_page" 
                                                            :key="pi"
                                                            :selected="pageNumber == imei_lists.current_page">{{pageNumber}}
                                                        </option>
                                                    </select>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    @click="getIMEIList(imei_lists.next_page_url);" 
                                                    :disabled="imei_lists.next_page_url===null">Next
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                            </div>



                            <div class="col-7">
                                <div class="card">
                                    <div class="card-header"><h4>Item Code: {{ slctd_item }}</h4></div>
                                    <div class="card-body">
                                        <div class="row justify-content-end mt-2">
                                            <div class="col-3">
                                                <!-- <form class="w-100 me-3" role="search">
                                                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                                        v-model="searchThis"
                                                        @keyup="search()">
                                                </form> -->
                                            </div>
                                        </div>

                                        <div class="">
                                            <table class="table table-hover table-striped table-dark">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th>Serial 1</th>
                                                        <th>Serial 2</th>
                                                        <th>Server IMEI Status</th>
                                                        <th>Store IMEI Status</th>
                                                        <!-- <th>Exist</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="slctd_item" v-for="item, index in this.item_details[slctd_item]" :key="index" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                                        @click="this.$refs.serialTransactionModal.getSerialTransactionHistory(filter.store.warehouse_code, slctd_item, item.IntrSerial, item.SuppSerial )">
                                                        <td>{{ item.IntrSerial }}</td>
                                                        <td>{{ item.SuppSerial }}</td>
                                                        <td v-html="getIMEIStatus(item.ServerSerialStatus)"></td>
                                                        <td v-html="getIMEIStatus(item.StoreSerialStatus)"></td>
                                                        <!-- <td v-html="isIMEIExistInStore(index)"></td> -->
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>                                        
                                    </div>
                               </div>
                            </div>
                        </div>

                
               
            </div>
        </div>
    </div>

    <serialTransactionModal ref="serialTransactionModal"
        :warehouse_code="filter.store.warehouse_code" :item_code="slctd_item" >
    </serialTransactionModal>
</template>

<script>
import serialTransactionModal  from '../../components/Modal/serial_transaction.vue';
export default{
    setup() {
        // const layoutProps = useForLayout();

        // return { layoutProps }
    },
    
    components: {
        serialTransactionModal,
    },

    async mounted(){
        await this.getIMEIList(this.url.imei_lists);
        this.filter.store.warehouse_code = this.$route.params.warehouse_code;
        this.content_loader = false;
    },

    data(){
        return{
            content_loader: true,
            imei_lists: {},
            item_details: {},
            slctd_item: '',
            filter: {
                store: {
                    ip_address: '',
                    warehouse_code: '',
                },
            },

            url:{
                imei_lists: '/api/imei-lists',
                ping_store: '/api/store/ping-store',
            },

           
            searchThis: '',
        }
    },

    methods:{

        async getIMEIList(URL){
            await axios.post(URL,{
                searchThis : this.searchThis,
                warehouseCode : this.$route.params.warehouse_code,
            }).then(async(response)=>{
                this.imei_lists = response.data    
        
                if(await this.pingStore(this.imei_lists.data[0].store_ip)){
                        // for (let index = 0; index < this.imei_lists.data.length; index++) {           
                        //     this.searchIMEI(index, this.imei_lists.data[index].store_ip, this.imei_lists.data[index].IntrSerial);
                        // }   
                        for (let index = 0; index < this.imei_lists.data.length; index++) {     
                            this.checkSerialStatus(index, this.imei_lists.data[index].store_ip, this.imei_lists.data[index].ItemCode);
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

                
                     
            });
        },

        search(){
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.getIMEIList(this.url.imei_lists)        
            }, 300);
        },

        getIMEIStatus(Serial_Status){
            if(Serial_Status == '1'){
                return '<button class="btn btn-danger">Not Available</button>';
            }else{
                return '<button class="btn btn-success">Available</button>';
            }
            
        },

        async pingStore(IPAddress){
            const response =  await axios.post(this.url.ping_store,{
                ip_address: IPAddress
            })

            return response.data
        },

        searchIMEI(Index, IPAddress, IMEI){
            this.imei_lists.data[Index].Exist = 3;

            axios.post('/api/search-imei',{
                imei: IMEI,
                ip_address: IPAddress
            }).then(response=>{
                if(response.data){
                    this.imei_lists.data[Index].Exist = 1;
                }else{
                    this.imei_lists.data[Index].Exist = 0;
                }
            })
        },

        checkSerialStatus(Index, IPAddress, ItemCode)
        {
            axios.post('/api/store/check-serial',{                
                ip_address: IPAddress,
                item_code: ItemCode,
            }).then(async (response)=>{

                for (const key in this.imei_lists.data) {
                    // console.log();
                    if(this.imei_lists.data[key].ItemCode == ItemCode){
                        console.log(ItemCode+': '+key);
                        this.imei_lists.data[key].StoreSerialQty = response.data[1].length
                    }
                    
                }

                await response.data[0].forEach(element => {

                    // if(!this.item_details?.[element.ItemCode]){
                    if(this.item_details[element.ItemCode]?.[0]){
                        let item_arr_length = this.item_details[element.ItemCode].length

                        this.item_details[element.ItemCode][item_arr_length] = {
                            IntrSerial : element.IntrSerial,
                            SuppSerial : element.SuppSerial,
                            ServerSerialStatus : element.ServerSerialStatus,
                            StoreSerialStatus: 0,
                            Exist : element.Exist
                        }
                    }else{
                        this.item_details[element.ItemCode] = []
                        
                        this.item_details[element.ItemCode][0] = {
                        IntrSerial : element.IntrSerial,
                        SuppSerial : element.SuppSerial,
                        ServerSerialStatus : element.ServerSerialStatus,
                        StoreSerialStatus: 0,
                        Exist : element.Exist
                        }
                    }
                })

                
                
                // if(ItemCode == '01-S01-A042 32GB'){
                //     console.log(response.data);
                // }

                // console.log(response.data[0]);
                // // console.log('Item Details')
                // // console.log(this.item_details);
                // console.log(ItemCode);
                // console.log('Length');
                // console.log(response.data[1].length);
                // console.log('----------------');
                // // console.log(this.item_details[ItemCode][1]['IntrSerial']);
                // for (let index = 0; index < response.data[1].length; index++) {
                //     console.log(index);
                //     // console.log(this.item_details[ItemCode][index]['IntrSerial']);
                // }
                // console.log('----------------');


                for (let index = 0; index < response.data[1].length; index++) {

                    if(!this.item_details[ItemCode]?.[index]){
                        let item_arr_length = this.item_details[ItemCode].length
                            
                        // this.item_details[ItemCode][item_arr_length] = {
                        //     IntrSerial : 'testing',
                        //     SuppSerial :'testing',
                        //     ServerSerialStatus : '3',
                        //     StoreSerialStatus: 'testing',
                        //     Exist : 'testing'
                        // }
                    }



                    let store_serial_status = response.data[1][index]['StoreSerialStatus'];
                    let item_code = response.data[1][index]['ItemCode'];
                    
                    
                    let server_serial_status = this.item_details[item_code][index]['ServerSerialStatus'];
                    let exist = this.item_details[item_code][index]['Exist'];

                    this.item_details[item_code][index]['StoreSerialStatus'] = store_serial_status;
                    this.item_details[item_code][index]['Exist'] = exist ='1';
                    

                    
                    this.countOfSummary(Index,server_serial_status, store_serial_status, exist)
                }
            })
        },

        countOfSummary(Index, ServerSerialStatus, StoreSerialStatus, ExistStatus)
        {   
            if(ServerSerialStatus == 0){
                this.imei_lists.data[Index]['ServerSerialAvailableCount'] = Number(this.imei_lists.data[Index]['ServerSerialAvailableCount']) + 1;
            }

            if(StoreSerialStatus == 0){
                this.imei_lists.data[Index]['StoreSerialAvailableCount'] = Number(this.imei_lists.data[Index]['StoreSerialAvailableCount']) + 1;
            }

            if(ExistStatus == 1){
                this.imei_lists.data[Index]['SerialExistingCount'] = Number(this.imei_lists.data[Index]['SerialExistingCount']) + 1;
            }
        },

        /*
        |==========================================================================
        | Jump Pagination
        |==========================================================================
        */
        jumPage(_value){            
            var url = this.url.imei_lists + `?page=${_value.target.value}`;
            this.getIMEIList(url);
        },
    },
    

    computed: {
        isIMEIExistInStore(){
            var value = (Index) => {
                
                var exist_status =  this.item_details[this.slctd_item]?.[Index]['Exist'] == undefined? '' : this.item_details[this.slctd_item]?.[Index]['Exist'];
                
                if(exist_status == 3){
                    return '<button type="button" class="btn btn-secondary">Checking...</button>';
                }else if(exist_status == 1){
                    return '<button class="btn btn-success">Existing</button>';
                } else {
                    return '<button type="button" class="btn btn-danger">Not Existing</button>';
                }
            }

            return value;
        }
    }
}
</script>