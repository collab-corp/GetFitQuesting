
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueLazyload from 'vue-lazyload';
Vue.use(VueLazyload);

import VModal from 'vue-js-modal';
Vue.use(VModal);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('alert-solid', require('./components/AlertSolid.vue'));
Vue.component('image-modal', require('./components/ImageModal.vue'));
Vue.component('news-card', require('./components/NewsCard.vue'));
Vue.component('testimonial-card', require('./components/TestimonialCard.vue'));

require('./filters');

const app = new Vue({
    el: '#app',

    components: {
        VueLazyload,
        VModal
    }
});
