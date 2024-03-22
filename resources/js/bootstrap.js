// /**
//  * We'll load the axios HTTP library which allows us to easily issue requests
//  * to our Laravel back-end. This library automatically handles sending the
//  * CSRF token as a header based on the value of the "XSRF" token cookie.
//  */
// import { startFireworks } from './fireworks.js';

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: 'ap2',
//     forceTLS: true
// });


import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Alpine.directive('clipboard', (el) => {
//     let text = el.textContent

//     el.addEventListener('click', () => {
//         navigator.clipboard.writeText(text)
//     })
// })

// Livewire.start()


// window.onload = function()
// {
//     axios.get('my_url')
//         .then(res => console.log(res.data))
//         .catch(err => console.log(err));
// }








