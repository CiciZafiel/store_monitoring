<template>
    <div>
        <div class="container-fluid pb-2">
            <div class="d-grid gap-3" style="grid-template-columns: 1fr 1fr 1fr 1fr 2fr;">
                <div class="card">
                    <div class="card-header">
                        <h3>Total Unposted to SAP</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalUnpostedSAP}}</h2>
                        </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>SAP Posted Today</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{ totalPostedSAP }}</h2>
                        </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Server Posted Today</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalPostedServer}}</h2>
                        </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>SAP Unposted Today</h3>
                    </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{totalUnpostedSAPToday}}</h2>
                        </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <form class="w-100 me-3" role="search">
                            <input type="search" class="form-control" placeholder="Search..." aria-label="Search"
                                v-model="searchThis"
                                @keyup="search()">
                        </form>
                    </div>
                        <div class="card-body ">
                            <h2 class="card-title text-end">Stores</h2>
                            <p class="card-text text-end">{{ 'Showing '+ store_lists.from +' to '+ store_lists.to +' of '+store_lists.total +' records.' }}</p>
                        </div>
                </div>
            </div>
        </div>

        <div class="b-example-divider"></div>

        <div class="bg-body-tertiary border rounded-3">  
                    <table class="table table-hover table-striped table-dark">
                        <thead>
                            <tr>
                            <th scope="col">Warehouse Code</th>
                            <th scope="col">Store</th>
                            <th scope="col">IP</th>
                            <th scope="col">Network Status</th>
                            <th scope="col">Ping</th>
                            </tr>
                        </thead>
                        <tbody id="storeAvailabilityTBL">
                            <tr v-for="store, index in store_lists.data" :key="index">
                                
                                
                                <td >
                                    <router-link class="text-decoration-none text-white" :class="getCursor(index)" 
                                        :to="store.store_availability == '1'? getRouterLink('store', {warehouse_code: store.warehouse_code} ) : ''">
                                        <p >{{ store.warehouse_code }}</p>
                                    </router-link>
                                </td>
                                <td>
                                    <router-link class="text-decoration-none text-white" :class="getCursor(index)"
                                        :to="store.store_availability == '1'? getRouterLink('store', {warehouse_code: store.warehouse_code} ) : ''">
                                        <p >{{ store.store_name }}</p>
                                    </router-link>
                                </td>
                                <td>
                                    <router-link class="text-decoration-none text-white" :class="getCursor(index)"
                                        :to="store.store_availability == '1'? getRouterLink('store', {warehouse_code: store.warehouse_code} ) : ''">
                                        <p>{{ store.store_ip }}</p>
                                    </router-link>
                                </td>

                                <td v-html="storeAvailability(index)"></td>
                                <td>
                                    <button @click="pingStoreIP(index, store.store_ip)" class="btn btn-primary"><i class="fas fa-redo"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- 
                    |==========================================================================
                    | Pagination loader.pagination=true;
                    |==========================================================================
                    -->           
                    <div class="d-flex justify-content-center mb-2">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" class="btn btn-secondary"
                                @click="getStoreList(store_lists.prev_page_url);" 
                                :disabled="store_lists.prev_page_url===null">Previous
                            </button>
                            <div class="btn-group" role="group">
                                <select class="px-2">
                                    <option v-for="(pageNumber, pi) in store_lists.last_page" 
                                        :key="pi"
                                        :selected="pageNumber == store_lists.current_page">{{pageNumber}}
                                    </option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary"
                                @click="getStoreList(store_lists.next_page_url);" 
                                :disabled="store_lists.next_page_url===null">Next
                            </button>
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
        this.getStoreList('/api/store-lists');
        this.totalUnpostedToSap('/api/total-unposted-to-sap');
        this.totalPostedToSAP('/api/total-posted-to-sap');
        this.totalPostedToServer('/api/total-posted-to-server');
        this.totalUnpostedToSAPToday('/api/total-unposted-to-sap-today');
    },

    data(){
        return{
            store_lists: {},
            ping_store: '',
            searchThis: '',
            totalUnpostedSAP: '',
            totalPostedSAP: '',
            totalPostedServer: '',
            totalUnpostedSAPToday: '',
        }
    },

    methods:{
        /*
        |==========================================================================
        | Display Store List
        |==========================================================================
        */
        async getStoreList(URL){
            await axios.post(URL,{
                searchThis : this.searchThis,
            }).then(response=>{
                this.store_lists = response.data
                
                for (let index = 0; index < this.store_lists.data.length; index++) {           
                    this.pingStoreIP(index, this.store_lists.data[index].store_ip);
                }
            });
        },


        /*
        |==========================================================================
        | Ping Store IP Address
        |==========================================================================
        */
        pingStoreIP(Index, IPAddress){
            this.store_lists.data[Index].store_availability = 3;
            axios.post('/api/store-availability',{ip: IPAddress}).then(response=>{
                if(response.data){
                    this.store_lists.data[Index].store_availability = 1;
                }else{
                    this.store_lists.data[Index].store_availability = 0;
                }
            })
        },
 

        /*
        |==========================================================================
        | Ping Store IP Address
        |==========================================================================
        */
        getRouterLink(LinkName, Params){
            return {name: LinkName, params: Params}
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

    computed: {
        getCursor(){
            return (index) =>{
                var store_availability = this.store_lists.data[index].store_availability;
                return store_availability == "1"? '' : 'not-allowed';
            }
        },

        storeAvailability(){
            var value = (index) => {
                var store_availability = this.store_lists.data[index].store_availability;
                if(store_availability == 3){
                    return '<button type="button" class="btn btn-secondary">Pinging...</button>';
                }else if(store_availability == 1){
                    return '<button type="button" class="btn btn-success">Online</button>';
                } else {
                    return '<button type="button" class="btn btn-danger">Offline</button>';
                }
            }

            return value;
        },
    }
}
</script>