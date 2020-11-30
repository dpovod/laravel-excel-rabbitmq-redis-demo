/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import BootstrapVue from 'bootstrap-vue'
import axios from 'axios'
window.axios = axios;
import Vue from 'vue'
window.Vue = require('vue');
Vue.use(BootstrapVue);
import VueNoty from 'vuejs-noty'
Vue.use(VueNoty)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuejs-noty/dist/vuejs-noty.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// common components
Vue.component('pagination', require('./components/common/Pagination').default);
Vue.component('progress-bar', require('./components/common/ProgressBar').default);
Vue.component('import-status-notifier', require('./components/common/ImportStatusNotifier').default);

// components
Vue.component('excel-rows-table', require('./components/ExcelRowsTable.vue').default);
Vue.component('excel-upload-form', require('./components/ExcelUploadForm.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
