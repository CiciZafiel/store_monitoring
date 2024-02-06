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

// Package

// Sweetalert2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp(application);
app.use(router)
    .use(VueSweetalert2)
    .component('content_loader', content_loader)
    .mount('#app');

 