/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

function envValue(key, fallback) {
    const v = import.meta.env[key];
    if (v == null || v === '' || String(v).includes('${')) {
        return fallback;
    }
    return v;
}

const reverbKey = envValue('VITE_REVERB_APP_KEY', '');
const reverbHost = envValue('VITE_REVERB_HOST', 'localhost');
const reverbPort = Number(envValue('VITE_REVERB_PORT', '6001')) || 6001;
const reverbScheme = envValue('VITE_REVERB_SCHEME', 'http');

if (import.meta.env.DEV) {
    console.log('[Echo] Config:', { hasKey: !!reverbKey, keyLength: reverbKey?.length, host: reverbHost, port: reverbPort });
}
function getStoredToken() {
    try {
        const data = localStorage.getItem('chancery_auth');
        if (!data) return null;
        const parsed = JSON.parse(data);
        return parsed?.tokenData?.access_token || parsed?.access_token || null;
    } catch {
        return null;
    }
}

if (reverbKey) {
    const token = getStoredToken();
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: reverbHost,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: reverbScheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: token ? { Authorization: `Bearer ${token}` } : {},
        },
    });
} else {
    console.warn('[Echo] VITE_REVERB_APP_KEY не задан — WebSocket отключён');
}
