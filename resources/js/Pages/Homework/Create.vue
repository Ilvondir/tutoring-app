<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, ref} from "vue";
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
const options = ref([{'id': 0, 'name': ''}]);

let form = useForm({
    title: '',
    student_id: 0,
});

onMounted(() => {
    axios.get(route('users.getStudentsToSelect'))
        .then((response) => {
            options.value = response.data;
        })
});

const submitForm = () => {
    form.post(route('homeworks.store'), {
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
        title="Prace domowe"
    >
        <template #header>
            <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight">
                <Link class="no-underline text-[#14b069]" :href="route('homeworks.index')">
                    <span>Prace domowe</span>
                </Link>
                / <span v-if="form.title.length<50">{{ form.title }}</span>
                <span v-else>{{ form.title.substring(0, 49) + "..." }}</span>
            </h2>
        </template>

        <form @submit.prevent="submitForm()">
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                    <div class="flex flex-col">
                        <div class="p-6">

                            <div>
                                <JetLabel for="title" value="Tytuł"/>
                                <JetInput id="title" v-model="form.title" type="text" placeholder="Tytuł"
                                          class="block w-full" required/>
                                <div class="error-message" v-if="errors.title">{{ errors.title }}</div>
                            </div>

                            <div class="mt-4">
                                <JetLabel for="student_id" value="Student"/>
                                <select
                                    id="student_id"
                                    v-model="form.student_id"
                                    class="block w-full rounded-md border border-gray-300 dark:border-gray-700 dark:bg-black bg-gray-200 text-black dark:text-gray-400 shadow-sm focus:border-[#4F46E5] focus:ring focus:ring-[#4F46E5] focus:ring-opacity-50"
                                    required
                                >
                                    <option value="0" disabled>Wybierz studenta</option>
                                    <option v-for="student in options" :key="student.id" :value="student.id">
                                        {{ student.name }}
                                    </option>
                                </select>
                                <div class="error-message" v-if="errors.student_id">{{ errors.student_id }}</div>
                            </div>

                            <div class="flex justify-end mt-5">

                                <ReturnButton/>

                                <JetButton
                                    type="submit"
                                    class="flex ml-2 items-center bg-[#14b069] hover:bg-[#048029] border-[#048029] hover:border-[#14b069] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10"
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
