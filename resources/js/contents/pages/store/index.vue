<template>
    <content_loader v-if="content_loader"></content_loader>
    <div v-else>
        <div class="container-fluid pb-3">
            <div class="d-grid gap-3" style="grid-template-columns: 1fr;">
                <div class="bg-body-tertiary border rounded-3 card">
                    <div class="card-header">
                        <h2>{{ imei_lists.data[0].store_name}}</h2>
                    </div>
                    <table class="table table-hover table-striped table-dark">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>IMEI</th>
                                <th>IMEI Status</th>
                                <th>Exist</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item, index in imei_lists.data" :key="index">
                                <td>{{ item.ItemCode }}</td>
                                <td>{{ item.IntrSerial }}</td>
                                <td v-html="getIMEIStatus(item.SerialStatus)"></td>
                                <td v-html="isIMEIExistInStore(index)"></td>
                            </tr>
                        </tbody>
                    </table>


                    <!-- 
                    |==========================================================================
                    | Pagination 
                    |==========================================================================
                    -->           
                    <div class="d-flex justify-content-center mb-2">
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
    </div>
</template>

<script>
// import { useForLayout } from '../../../../peneya/layout.js';
export default{
    setup() {
        // const layoutProps = useForLayout();

        // return { layoutProps }
    },

    async mounted(){
        await this.getIMEIList(this.url.imei_lists);
        this.content_loader = false;
    },

    data(){
        return{
            content_loader: true,
            url:{
                imei_lists: '/api/imei-lists',
            },

            imei_lists: {},
            searchThis: '',
        }
    },

    methods:{
        /*
        |==========================================================================
        | Display IMEI List
        |==========================================================================
        */
        async getIMEIList(URL){
            await axios.post(URL,{
                searchThis : this.searchThis,
                warehouseCode : this.$route.params.warehouse_code,
            }).then(response=>{
                this.imei_lists = response.data    
                
                for (let index = 0; index < this.imei_lists.data.length; index++) {           
                    this.searchIMEI(index, this.imei_lists.data[index].store_ip, this.imei_lists.data[index].IntrSerial);
                }        
            });
        },


        /*
        |==========================================================================
        | Search
        |==========================================================================
        */
        search(){
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.getStoreList('/api/store-lists')        
            }, 300);
        },

        /*
        |==========================================================================
        | IMEI Status
        |==========================================================================
        */
        getIMEIStatus(Serial_Status){
            if(Serial_Status == '1'){
                return '<button class="btn btn-danger">Not Available</button>';
            }else{
                return '<button class="btn btn-success">Available</button>';
            }
            
        },

        /*
        |==========================================================================
        | IMEI Status
        |==========================================================================
        */
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
                var exist_status =  this.imei_lists.data[Index].Exist
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