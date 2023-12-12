// Store/index.js
import { createStore } from 'vuex';

export default createStore({
    state: {
        isLoggedIn: localStorage.getItem('refresh_token') !== null,
        user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null,
    },
    mutations: {
        setLoginStatus(state, isLoggedIn) {
            state.isLoggedIn = isLoggedIn;
        },
        setUser(state, user) {
            state.user = user;
        }
    },
});
