import { reactive, computed } from 'vue';
import { api } from '../api';

// Declared OUTSIDE the function so every component shares the same state
const state = reactive({
    user: null,
    token: localStorage.getItem('token'),
});

export function useAuth() {
    const isLoggedIn = computed(() => !!state.token && !!state.user);
    const isAdmin = computed(() => state.user?.role === 'admin');

    function clear() {
        state.token = null;
        state.user = null;
        localStorage.removeItem('token');
    }

    async function login(email, password) {
        const res = await api('/login', { method: 'POST', body: { email, password } });
        localStorage.setItem('token', res.token);
        state.token = res.token;
        state.user = res.user;
    }

    async function restore() {
        if (!state.token) return;
        try {
            const res = await api('/me');
            state.user = res.user;
        } catch {
            clear();
        }
    }

    async function logout() {
        try {
            await api('/logout', { method: 'POST' });
        } catch {
            /* token may already be invalid; clear locally anyway */
        }
        clear();
    }

    return { state, isLoggedIn, isAdmin, login, logout, restore, clear };
}