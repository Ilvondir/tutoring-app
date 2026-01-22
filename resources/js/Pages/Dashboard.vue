<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, usePage} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import 'boxicons';

const page = usePage();
const time = ref('');

onMounted(() => {
    const elapsed = page.props.stats.student.learning_time;
    const hours = String(Math.floor(elapsed / 3600)).padStart(2, '0')
    const minutes = String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0')
    const seconds = String(elapsed % 60).padStart(2, '0')
    time.value = `${hours}:${minutes}:${seconds}`;
})
</script>

<template>
    <AppLayout title="Strona główna">
        <template #header>
            <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight">
                Strona główna
            </h2>
        </template>

        <div class="pt-12 pb-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg  p-8">
                    <h1 class="text-xl">
                        Witaj, {{ page.props.auth.user.name }}!
                    </h1>
                </div>
            </div>
        </div>

        <div class="pb-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg  p-8">

                    <!-- Teacher block -->
                    <template v-if="page.props.user.roles.includes('teacher')">
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-6/12 xl:pr-3">
                                <div
                                    class="relative flex flex-col min-w-0 break-words shadowCards dark:bg-gray-900 bg-white rounded mb-5 xl:mb-0 shadow-lg">
                                    <Link :href="route('homeworks.index')">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="uppercase font-bold text-xs">
                                                        Liczba dodanych prac domowych
                                                    </h5>
                                                    <span class="font-semibold text-xl">
                                                    {{ page.props.stats.teacher.created_homeworks }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-[#4F46E5]">
                                                        <box-icon name='receipt'></box-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>

                            <div class="w-full lg:w-6/12 xl:pl-3">
                                <div
                                    class="relative flex flex-col min-w-0 break-words shadowCards dark:bg-gray-900 bg-white rounded xl:mb-0 shadow-lg">
                                    <Link :href="route('homeworks.index')">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">
                                                        Liczba dodanych zadań
                                                    </h5>
                                                    <span class="font-semibold text-xl text-blueGray-700">
                                                    {{ page.props.stats.teacher.created_exercises }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-[#4F46E5]">
                                                        <box-icon name='pen'></box-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Student block -->
                    <template v-if="page.props.user.roles.includes('student')">
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-4/12 xl:pr-2">
                                <div
                                    class="relative flex flex-col min-w-0 break-words shadowCards dark:bg-gray-900 bg-white rounded mb-5 xl:mb-0 shadow-lg">
                                    <Link :href="route('homeworks.index')">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="uppercase font-bold text-xs">
                                                        Ukończonych prac domowych
                                                    </h5>
                                                    <span class="font-semibold text-xl">
                                                    {{ page.props.stats.student.finished_homeworks }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-[#4F46E5]">
                                                        <box-icon name='receipt'></box-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>

                            <div class="w-full lg:w-4/12 xl:px-2">
                                <div
                                    class="relative flex flex-col min-w-0 break-words shadowCards dark:bg-gray-900 bg-white rounded mb-5 xl:mb-0 shadow-lg">
                                    <Link :href="route('homeworks.index')">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="uppercase font-bold text-xs">
                                                        Ukończonych zadań
                                                    </h5>
                                                    <span class="font-semibold text-xl">
                                                    {{ page.props.stats.student.finished_exercises }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-[#4F46E5]">
                                                        <box-icon name='pen'></box-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>

                            <div class="w-full lg:w-4/12 xl:pl-2">
                                <div
                                    class="relative flex flex-col min-w-0 break-words shadowCards dark:bg-gray-900 bg-white rounded xl:mb-0 shadow-lg">
                                    <Link :href="route('homeworks.index')">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                    <h5 class="text-blueGray-400 uppercase font-bold text-xs">
                                                        Czas spędzony przy nauce
                                                    </h5>
                                                    <span class="font-semibold text-xl text-blueGray-700">
                                                    {{ time }}
                                                </span>
                                                </div>
                                                <div class="relative w-auto pl-4 flex-initial">
                                                    <div
                                                        class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-[#4F46E5]">
                                                        <box-icon name='time'></box-icon>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </template>


                </div>
            </div>
        </div>


    </AppLayout>
</template>
<style>
.shadowCards {
    box-shadow: 4px 4px 10px #00000088;
}
</style>
