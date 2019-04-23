
require('./bootstrap');
import router from './routes.js';
import Vue from 'vue'

//Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app',
    router,
});

Vue.nextTick(function () {
    $(function () {
        $('[data-toggle="popover"]').popover();
    })

});
