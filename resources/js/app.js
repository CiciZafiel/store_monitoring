import './bootstrap';
// import jQuery from 'jquery';
// window.$ = jQuery;

import {createApp} from 'vue';
// Router
import router from './routes/router.js';

// Content
import application from './app.vue';

// Component
import content_loader from './contents/components/content_loader.vue';



const app = createApp(application);
app.use(router)
    .component('content_loader', content_loader)
    .mount('#app');

 