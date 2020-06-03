import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add a request interceptor
window.axios.interceptors.request.use(function (config) {
    $(".loading-holder").css("display", "block");
    return config;
}, function (error) {
    $(".loading-holder").css("display", "none");
    return Promise.reject(error);
});

// Add a response interceptor
window.axios.interceptors.response.use(function (response) {
    $(".loading-holder").css("display", "none");
    return response;
}, function (error) {
    $(".loading-holder").css("display", "none");
    return Promise.reject(error);
});

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {

    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = Vue;
window.Vuex = Vuex;

Vue.use(Vuex);
