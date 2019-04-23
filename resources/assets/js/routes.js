import VueRouter from 'vue-router';


let routes=[
    {
        path: '/',
        name: 'index',
        component:require('./components/index.vue'),
    },
    {
        path: '/home',
        name: 'home',
        component:require('./components/home.vue'),
    }
];


export default new VueRouter({
    routes,
    mode: 'history'
});