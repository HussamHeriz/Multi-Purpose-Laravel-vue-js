import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Dashboard from './components/Dashboard.vue';
import Profile from './components/Profile.vue';
import Users from './components/Users.vue';
import Developer from './components/Developer.vue';
import moment from 'moment';

const routes = [
    { path: '/dashboard', component: Dashboard },
    { path: '/profile', component: Profile },
    { path: '/developer', component: Developer },
    { path: '/users', component: Users },
];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

const app = new Vue({
    el: '#app',
    router,
});

Vue.filter('upFirstLetter', function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function(date) {
    return moment(date).format('MMMM Do YYYY, h:mm:ss a');
});
