<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, ref} from "vue";
import JetButton from "@/Components/Button.vue";
import JetLabel from "@/Components/Label.vue";
import JetInput from "@/Components/Input.vue";
import 'boxicons';
import ReturnButton from "@/Components/ReturnButton.vue";
import {useToast} from "vue-toastification";
import Toast from "@/Components/Toast.vue";
import Checkbox from "@/Components/Checkbox.vue";

const props = defineProps({
    item: Object,
});

const form = useForm({
    id: props.item.id,
    name: props.item.name,
    email: props.item.email,
    roles: props.item.roles,
});
</script>

<template>
    <AppLayout
        title="Użytkownicy"
    >
        <template #header>
            <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight">
                <Link class="no-underline text-[#4F46E5]" :href="route('users.index')">
                    <span>Użytkownicy</span>
                </Link>
                / <span v-if="form.name?.length<50">{{ form.name }}</span>
                <span v-else>{{ form.name?.substring(0, 49) + ".." }}</span>
            </h2>
        </template>

        <form>
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                    <div class="flex flex-col">
                        <div class="p-6">

                            <p class="text-black dark:text-gray-400"><span class="font-bold">Data utworzenia:</span>
                                {{ props.item.created_at }}</p>
                            <p class="text-black dark:text-gray-400"><span
                                class="font-bold">Data ostatniej edycji:</span> {{ props.item.updated_at }}</p>

                            <div class="mt-5">
                                <JetLabel for="name" value="Nazwa"/>
                                <JetInput id="name" v-model="form.name" type="text" placeholder="Nazwa"
                                          class="block w-full" disabled/>
                            </div>

                            <div class="mt-5">
                                <JetLabel for="email" value="Email"/>
                                <JetInput id="email" v-model="form.email" type="text"
                                          placeholder="Email" class="block w-full" disabled/>
                            </div>

                            <div class="mt-5">
                                <JetLabel for="email" value="Role:"/>
                                <ul>
                                    <li v-for="role in form.roles">{{ role }}</li>
                                </ul>
                            </div>

                            <div class="flex justify-end mt-5">

                                <ReturnButton/>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
