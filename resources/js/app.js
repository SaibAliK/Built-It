require('./bootstrap');

import Vue from 'vue/dist/vue';
import ResizeImage from './filters/resize-image'
import Language from './filters/language'
import StarRating from './filters/star-rating'
import Elapsed from './filters/elapsed'

import trans from './trans';
Vue.prototype.trans = trans;
window.Vue = new Vue;
import swal from 'sweetalert';
window.$swal = swal;
import 'sweetalert2/dist/sweetalert2.css';
import VueSweetalert2 from 'vue-sweetalert2';
import ar from '../lang/ar.json'
import en from '../lang/en.json'

window.translate = {'ar': ar, 'en': en};

Vue.use(VueSweetalert2);
Vue.filter('resizeImage', ResizeImage);
Vue.filter('language', Language);
Vue.filter('starRating', StarRating);
Vue.filter('elapsed', Elapsed);


Vue.component('notifications', require('./components/notification/notifications.vue').default);
Vue.component('no-data', require('./components/common/no-data.vue').default);
Vue.component('change-password', require('./components/common/change-password.vue').default);


const app = new Vue({
    el: '#main-app',
    data: {
        showBookingBreadCrumbs: true,
        showNotifications: false,
    },
});
