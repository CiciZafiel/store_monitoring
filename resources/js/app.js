import './bootstrap';
// import jQuery from 'jquery';
// window.$ = jQuery;

import {createApp} from 'vue';
// Content
import application from './app.vue';

// Router
import router from './routes/router.js';

const app = createApp(application);
console.log('test');
app.use(router);
app.mount('#app');

 