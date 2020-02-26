import app from './appbase';
import axios from 'axios'

axios.interceptors.request.use(function (config) {
    app.$store.commit('beginloading');
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

axios.interceptors.response.use(function (response) {
    app.$store.commit('endloading');
    if (!response.data.result) {
        app.$bvToast.toast(response.data.errorinfo, {
            title: '出了一点小问题',
            autoHideDelay: 4000,
            appendToast: true,
            variant: 'warning',
            solid: true
        });
    }

    return response;
}, function (error) {
    app.$store.commit('endloading');
    app.$bvToast.toast(`${error.status} 错误`, {
        title: '服务器出错了',
        autoHideDelay: 4000,
        appendToast: true,
        variant: 'danger',
        solid: true
    });
    return Promise.reject(error);
});