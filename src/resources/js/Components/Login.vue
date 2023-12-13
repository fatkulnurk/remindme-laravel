<script>
import Layout from '@/Layouts/DefaultLayout.vue'

export default {
    layout: Layout,
}
</script>
<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ofetch } from 'ofetch';
import {useStore} from "vuex";

const store = useStore(); // Access the Vuex Store
const page = usePage();
const appUrl = computed(() => page.props.app_url)

const email = ref('');
const password = ref('');
const error = ref(null);

const login = () => {
    ofetch('/api/session', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email.value, password: password.value }),
        ignoreResponseError: true
    })
        .then((response) => {
            if (!response) {
                error.value = 'Invalid response';
                throw new Error('Invalid response');
            }

            if (response.ok) {
                return response;
            } else {
                error.value = response.msg;
            }
        })
        .then((responseData) => {
            const { access_token, refresh_token,user } = responseData.data;

            localStorage.setItem('access_token', access_token);
            localStorage.setItem('refresh_token', refresh_token);
            localStorage.setItem('user', JSON.stringify(user));
            store.commit('setLoginStatus', true);
            store.commit('setUser', JSON.stringify(user));
        })
        .catch((error) => {
            console.error('Error during login:', error);
            error.value = 'An error occurred during login';
        })
        .finally(() => {
            console.log('Login process completed.');
        });
};
</script>

<template>
    <div class="my-8 max-w-md mx-auto space-y-4 p-8 rounded-xl">
        <div class="text-center space-y-4">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                Sign in to your account
            </h1>
            <p class="text-normal text-gray-500 dark:text-gray-400">
                User Demo alice@mail.com or bob@mail.com
            </p>
        </div>
        <div v-if="error" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ error }}
        </div>

        <form @submit.prevent="login" class="space-y-4">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email:</label>
                <input type="email"
                       v-model="email"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       required>
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password::</label>
                <input type="password"
                       v-model="password"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       required>
            </div>
            <div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Login</button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
