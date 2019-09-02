import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Dashboard from './components/Dashboard.vue';
import Profile from './components/Profile.vue';
import Users from './components/Users.vue';
import Developer from './components/Developer.vue';
import NotFound from './components/NotFound.vue';
import moment from 'moment';
import Axios from 'axios';

const routes = [
    { path: '/dashboard', component: Dashboard },
    { path: '/profile', component: Profile },
    { path: '/developer', component: Developer },
    { path: '/users', component: Users },
    { path: '/404', component: NotFound },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app',
    router,
    data: {
        search:''
    },
    methods: {
        searchit: _.debounce( () => {
                Fire.$emit('search');
            }, 1000),
        printme: function() {
            window.print();
        }
    }
});

Vue.filter('upFirstLetter', function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function(date) {
    return moment(date).format('MMMM Do YYYY, h:mm:ss a');
});
