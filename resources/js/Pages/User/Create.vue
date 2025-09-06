<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {ref} from "vue";
import JetButton from "@/Components/Button.vue";
import JetLabel from "@/Components/Label.vue";
import JetInput from "@/Components/Input.vue";
import 'boxicons';
import ReturnButton from "@/Components/ReturnButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Toast from "@/Components/Toast.vue";
import {useToast} from "vue-toastification";

const props = defineProps({
    errors: Object,
});

const toast = useToast();

let form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const submitForm = () => {
    form.post(route('users.store'), {
        onSuccess: () => {
            toast.success({
                component: Toast,
                props: {
                    operation: 'Operacja zakończona powodzeniem!'
                }
            });
        },
        onError: () => {
            toast.error({
                component: Toast,
                props: {
                    operation: 'Podczas wykonywania operacji wystąpił błąd.'
                }
            });
        },
    });
}

</script>

<template>
    <AppLayout
        title="Klienci"
    >
        <template #header>
            <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight">
                <Link class="no-underline text-[#4F46E5]" :href="route('users.index')">
                    <span>Użytkownicy</span>
                </Link>
                / <span v-if="form.name.length<50">{{ form.name }}</span>
                <span v-else>{{ form.name.substring(0, 49) + ".." }}</span>
            </h2>
        </template>

        <form @submit.prevent="submitForm()">
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                    <div class="flex flex-col">
                        <div class="p-6">

                            <div>
                                <JetLabel for="company_name" value="Nazwa"/>
                                <JetInput id="company_name" v-model="form.name" type="text" placeholder="Nazwa"
                                          class="block w-full" required/>
                                <div class="error-message" v-if="errors.name">{{ errors.name }}</div>
                            </div>

                            <div class="mt-5">
                                <JetLabel for="email" value="Email"/>
                                <JetInput id="email" v-model="form.email" type="text"
                                          placeholder="Email" class="block w-full" required/>
                                <div class="error-message" v-if="errors.email">{{ errors.email }}</div>
                            </div>

                            <div class="mt-5">
                                <JetLabel for="password" value="Hasło"/>
                                <JetInput id="password" v-model="form.password" type="password"
                                          placeholder="Hasło" class="block w-full" required/>
                                <div class="error-message" v-if="errors.password">{{ errors.password }}</div>
                            </div>

                            <div class="mt-5">
                                <JetLabel for="password_confirmation" value="Powtórz hasło"/>
                                <JetInput id="password_confirmation" v-model="form.password_confirmation"
                                          type="password"
                                          placeholder="Hasło" class="block w-full" required/>
                                <div class="error-message" v-if="errors.password_confirmation">
                                    {{ errors.password_confirmation }}
                                </div>
                            </div>

                            <div class="mt-5">
                                <JetLabel for="role" value="Rola"/>
                                <select id="role"
                                        class="block w-full rounded-md border border-gray-300 dark:border-gray-700 dark:bg-black bg-gray-200 text-black dark:text-gray-400 shadow-sm focus:border-[#4F46E5] focus:ring focus:ring-[#4F46E5] focus:ring-opacity-50"
                                        v-model="form.role" required>
                                    <option value="0" disabled>Wybierz rolę</option>
                                    <option value="student">Student</option>
                                    <option value="teacher">Nauczyciel</option>
                                </select>
                                <div class="error-message" v-if="errors.role">
                                    {{ errors.role }}
                                </div>
                            </div>

                            <div class="flex justify-end mt-5">

                                <ReturnButton/>

                                <JetButton
                                    type="submit"
                                    class="flex ml-2 items-center bg-[#4F46E5] hover:bg-[#2F26C5] border-[#2F26C5] hover:border-[#4F46E5] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10"
                                >
                                    <div class="flex items-center">
                                        <p class="mr-2 ">Zapisz</p>
                                        <box-icon name='plus-circle' color="white"></box-icon>
                                    </div>
                                </JetButton>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
