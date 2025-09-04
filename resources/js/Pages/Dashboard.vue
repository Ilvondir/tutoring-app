<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import {usePage} from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight">
                Strona główna
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg  p-8">
                    <h1 class="text-xl">
                        Witaj, {{ page.props.auth.user.name }}!
                    </h1>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
