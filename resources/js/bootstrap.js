window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.$ = window.jQuery = require('jquery');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest',
    'Authorization': window.Laravel.user_token
};


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';



window.Pusher = require('pusher-js');



window.Echo = new Echo({

    broadcaster: 'pusher',

    key: window.Laravel.websocketKey,

    cluster: window.Laravel.websocketCluster,

    forceTLS: false,

    auth: {

        headers: {

            'X-CSRF-Token': window.Laravel.csrfToken,

            'Authorization': `Bearer ${window.Laravel.user_token}`,

        }

    },

    wsHost: window.location.hostname,
    // wsPath: "/wsbuildit",

    wsPort: window.Laravel.websocketPort,

    authEndpoint: window.Laravel.websocketEndpoint,

    // authEndpoint: '/en/broadcasting/auth',

    disabledTransports: ['sockjs'],

    // enabledTransports: ['ws', 'wss']
    enabledTransports: ['ws']

});

