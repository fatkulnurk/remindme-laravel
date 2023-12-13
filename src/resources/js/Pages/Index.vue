<template>
    <default-layout>
        <div>
            <!-- Check if user is logged in -->
            <template v-if="isLoggedIn">
                <reminder/>
            </template>
            <template v-else>
                <Login/>
            </template>
        </div>
    </default-layout>
</template>

<script setup>
import {useStore} from 'vuex';
import Login from '@/Components/Login.vue';
import Reminder from '@/Components/Reminder.vue';
import {computed,onMounted} from "vue";
import {ofetch} from "ofetch";
import DefaultLayout from "../Layouts/DefaultLayout.vue";

const store = useStore();

const isLoggedIn = computed(() => store.state.isLoggedIn);

if (isLoggedIn) {

    const requestNewAccessToken = async () => {
        const response = await ofetch('/api/session', {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('refresh_token')}`
            },
            ignoreResponseError: true,
            async onResponse({ request, response, options }) {
                if (response._data.ok === false) {
                    localStorage.removeItem('access_token');
                    localStorage.removeItem('refresh_token');
                    store.commit('setLoginStatus', false);
                }
            },
        })

        if (response.ok) {
            localStorage.setItem('access_token', response.data.access_token);
        }
    };

    onMounted(() => {
        requestNewAccessToken();
    })
    setInterval(requestNewAccessToken, (10 * 1000));
}
</script>
