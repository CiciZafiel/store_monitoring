<template>
    <div>
        <div class="container-fluid pb-3">
            <div class="d-grid gap-3" style="grid-template-columns: 1fr;">
                <div class="bg-body-tertiary border rounded-3">
                    <table class="table table-hover table-striped table-dark">
                        <thead>
                            <tr>
                            <th scope="col">WarehouseCode</th>
                            <th scope="col">Store</th>
                            <th scope="col">IP</th>
                            <th scope="col">Status</th>
                            <th scope="col">Refresh</th>
                            </tr>
                        </thead>
                        <tbody id="storeAvailabilityTBL">
                            <tr v-for="store, index in store_lists" :key="index">
                                <th scope="row">{{ store.warehouse_code }}</th>
                                <td>{{ store.store_name }}</td>
                                <td>{{ store.store_ip}}</td>
                                <td v-html="storeAvailability(index)"></td>
                                <td>
                                    <button @click="pingStoreIP(index, store.store_ip)" class="btn btn-primary"><i class="fas fa-redo"></i></button>
                                </td>
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
                            <button type="button" class="btn btn-secondary">Previous</button>
                            <div class="btn-group" role="group">
                                <select class="">
                                    <option>Default select</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="b-example-divider"></div>

        <!-- <nav class="py-2 bg-body-tertiary border-bottom">
            <div class="container d-flex flex-wrap">
            <ul class="nav me-auto">
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2 active" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Features</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Pricing</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">FAQs</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">About</a></li>
            </ul>
            <ul class="nav">
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Login</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Sign up</a></li>
            </ul>
            </div>
        </nav>  -->
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
        this.getStoreList();
    },

    data(){
        return{
            store_lists: {},
            ping_store: '',
        }
    },

    methods:{
        /*
        |==========================================================================
        | Display Store List
        |==========================================================================
        */
        async getStoreList(){
            await axios.get('/api/store-lists').then(response=>{
                this.store_lists = response.data

                for (let index = 0; index < this.store_lists.length; index++) {                    
                    this.pingStoreIP(index, this.store_lists[index].store_ip);
                }
            });
        },


        /*
        |==========================================================================
        | Ping Store IP Address
        |==========================================================================
        */
        pingStoreIP(Index, IPAddress){
            console.log(1);
            axios.post('/api/store-availability',{ip: IPAddress}).then(response=>{
                if(response.data){
                    this.store_lists[Index].store_availability = 1;
                }else{
                    this.store_lists[Index].store_availability = 0;
                }
            })

        }
    },

    computed: {
        storeAvailability(){
            var value = (index) => {
                var store_availability = this.store_lists[index].store_availability;

                return store_availability == 1? '<button type="button" class="btn btn-success">Online</button>' : '<button type="button" class="btn btn-danger">Offline</button>';
            }

            return value;
        },
    }
}
</script>