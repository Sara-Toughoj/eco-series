require('./bootstrap');

require('alpinejs');

import Vue from 'vue';

window.Vue = require('vue');

Vue.component('task-list', require('./components/TaskList').default);

const app = new Vue({
    el: '#app'
});
