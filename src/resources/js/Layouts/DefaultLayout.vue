<script setup>
import {Link} from '@inertiajs/vue3'

import {usePage} from '@inertiajs/vue3';
import {computed} from "vue";

const page = usePage();
const appName = computed(() => page.props.app_name ?? 'RemindMe')
</script>

<template>
    <header class="border-b-2">
        <div class="bg-white border-gray-200 dark:bg-gray-900 pb-4 text-center">
            <span class="self-center text-3xl font-semibold whitespace-nowrap dark:text-white">{{ appName }}</span>
        </div>
    </header>
    <main>
        <slot/>
    </main>
    <footer class="text-center space-y-2 p-4">
        <p>&copy; 2023 - {{ appName }}</p>
        <p>Fatkul Nur Koirudin | fatkul@dibumi.com</p>
    </footer>
</template>
