<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {ref} from "vue";
import JetButton from "@/Components/Button.vue";
import JetLabel from "@/Components/Label.vue";
import JetInput from "@/Components/Input.vue";
import 'boxicons';
import ReturnButton from "@/Components/ReturnButton.vue";
import Toast from "@/Components/Toast.vue";
import {useToast} from "vue-toastification";
import TextArea from "@/Components/TextArea.vue";
import DialogModal from "@/Components/DialogModal.vue";

const props = defineProps({
    item: Object,
    errors: Object,
});

const toast = useToast();
const hasOpenModal = ref(false);
const hasOpenHistoryModal = ref(false);
const exerciseError = ref({})
const attempts = ref([])

let form = useForm({
    id: props.item.id,
    title: props.item.title,
});

const submitForm = () => {
    form.put(route('homeworks.update', form.id), {
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

let exerciseForm = useForm({
    id: 0,
    assignment: '',
    answer: '',
    homework_id: props.item.id,
});


const submitExerciseForm = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                component: Toast,
                props: {
                    operation: 'Operacja zakończona powodzeniem!'
                }
            });
            hasOpenModal.value = false;
        },
        onError: (res) => {
            toast.error({
                component: Toast,
                props: {
                    operation: 'Podczas wykonywania operacji wystąpił błąd.'
                }
            });
            exerciseError.value = res;
        },
    }

    if (exerciseForm.id > 0) {
        exerciseForm.put(route('exercises.update', exerciseForm.id), options);
    } else {
        exerciseForm.post(route('exercises.store'), options);
    }
}

const deleteExercise = (id) => {
    if (confirm('Czy na pewno chcesz usunąć to zadanie?')) {
        router.delete(route('exercises.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success({
                    component: Toast,
                    props: {
                        operation: 'Operacja zakończona powodzeniem!'
                    }
                });
                hasOpenModal.value = false;
            },
            onError: (res) => {
                toast.error({
                    component: Toast,
                    props: {
                        operation: 'Podczas wykonywania operacji wystąpił błąd.'
                    }
                });
                exerciseError.value = res;
            },
        });
    }
}

const setCreatingExercise = () => {
    exerciseForm.id = 0;
    exerciseForm.assignment = '';
    exerciseForm.answer = '';
    hasOpenModal.value = true;
}

const setEditingExercise = (id, index) => {
    exerciseForm.id = id;
    exerciseForm.assignment = props.item.exercises[index].assignment;
    exerciseForm.answer = props.item.exercises[index].answer;
    hasOpenModal.value = true;
}

const moveExercise = (id, direction) => {
    router.patch(route('exercises.move', id), {
        direction
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                component: Toast,
                props: {
                    operation: 'Operacja zakończona powodzeniem!'
                }
            });
            hasOpenModal.value = false;
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

const getExerciseHistory = (id) => {
    axios.get(route('attempts.index', id))
        .then(res => {
            toast.success({
                component: Toast,
                props: {
                    operation: 'Pomyślnie pobrano próby rozwiązania!'
                }
            });
            attempts.value = res.data;
            hasOpenHistoryModal.value = true;
        })
        .catch(() => {
            toast.error({
                component: Toast,
                props: {
                    operation: 'Podczas wykonywania operacji wystąpił błąd.'
                }
            });
        });
}

</script>

<template>
    <AppLayout
        title="Prace domowe"
    >
        <template #header>
            <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight">
                <Link class="no-underline text-[#4F46E5]" :href="route('homeworks.index')">
                    <span>Prace domowe</span>
                </Link>
                / <span v-if="form.title.length<50">{{ form.title }}</span>
                <span v-else>{{ form.title.substring(0, 49) + "..." }}</span>
            </h2>
        </template>

        <form @submit.prevent="submitForm()">
            <div class="pt-12 pb-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 dark:text-gray-400 text-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                    <div class="flex flex-col">
                        <div class="p-6">

                            <div>
                                <JetLabel for="title" value="Tytuł"/>
                                <JetInput id="title" v-model="form.title" type="text" placeholder="Tytuł"
                                          class="block w-full" required/>
                                <div class="error-message" v-if="errors.title">{{ errors.title }}</div>
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

        <div class="pb-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 dark:text-gray-400 text-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                <div class="flex flex-col">
                    <div class="p-6">

                        <h1 class="text-2xl mb-3 font-bold">Status</h1>

                        <template v-if="props.item.student">
                            <template v-if="props.item.complete_date">
                                <div class="text-[#14b069]">
                                    Praca domowa została ukończona {{ props.item.complete_date }} przez
                                    {{ props.item.student.name }}.
                                </div>
                            </template>
                            <template v-else>
                                <div class="text-red">
                                    Praca domowa nie została ukończona przez {{ props.item.student.name }}.
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <template v-if="props.item.complete_date">
                                <div class="text-[#14b069]">
                                    Praca domowa została ukończona {{ props.item.complete_date }}.
                                </div>
                            </template>
                            <template v-else>
                                <div class="text-red">
                                    Praca domowa nie została ukończona.
                                </div>
                            </template>
                        </template>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 dark:text-gray-400 text-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                <div class="flex flex-col">
                    <div class="p-6">

                        <div class="flex justify-between">
                            <div>
                                <h1 class="text-2xl mb-3 font-bold">Zadania</h1>
                            </div>
                            <div>
                                <JetButton
                                    @click="setCreatingExercise"
                                    type="submit"
                                    class="flex ml-2 items-center bg-[#4F46E5] hover:bg-[#2F26C5] border-[#2F26C5] hover:border-[#4F46E5] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10"
                                >
                                    <div class="flex items-center">
                                        <p class="mr-2 ">Dodaj</p>
                                        <box-icon name='plus-circle' color="white"></box-icon>
                                    </div>
                                </JetButton>

                            </div>
                        </div>


                        <template v-for="(exercise, index) in props.item.exercises">
                            <div class="flex items-center mt-5">
                                <h1 class="text-xl mb-1">Zadanie {{ exercise.order }}</h1>

                                <box-icon name="history" color="#4F46E5" size="s" class="cursor-pointer ml-3"
                                          @click="() => getExerciseHistory(exercise.id)"></box-icon>

                                <box-icon name="pencil" color="#4F46E5" size="s" class="cursor-pointer ml-4"
                                          @click="() => setEditingExercise(exercise.id, index)"></box-icon>

                                <box-icon name="trash" color="#4F46E5" size="s" class="cursor-pointer ml-1"
                                          @click="() => deleteExercise(exercise.id)"></box-icon>

                                <box-icon name="chevron-up" color="#4F46E5" size="s"
                                          class="cursor-pointer ml-4" v-if="index !== 0"
                                          @click="() => moveExercise(exercise.id, 1)"></box-icon>
                                <box-icon name="chevron-down" color="#4F46E5" size="s"
                                          :class="'cursor-pointer ' + (index === 0 ? 'ml-4' : 'ml-1')"
                                          v-if="index !== props.item.exercises.length-1"
                                          @click="() => moveExercise(exercise.id, 0)"></box-icon>
                            </div>


                            <hr/>
                            <TextArea style="width: 100%" class="mt-2" v-model="exercise.assignment"
                                      disabled></TextArea>
                            <JetInput id="title" v-model="exercise.answer" type="text" placeholder="Odpowiedź"
                                      class="block w-full" disabled/>
                            <div class="text-[#14b069]" v-if="exercise.complete_date">
                                Rozwiązane {{ exercise.complete_date }}.
                            </div>
                            <div class="text-red" v-else>
                                Nie rozwiązane.
                            </div>
                        </template>


                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <form @submit.prevent="submitExerciseForm">
        <DialogModal :show="hasOpenModal">
            <template #title>
                <template v-if="exerciseForm.id > 0">
                    Edytuj zadanie
                </template>
                <template v-else>
                    Dodaj zadanie
                </template>
            </template>


            <template #content>

                <div>
                    <JetLabel for="assignment" value="Polecenie"/>
                    <TextArea id="assignment" v-model="exerciseForm.assignment" type="text" placeholder="Polecenie"
                              class="block w-full" required/>
                    <div class="error-message" v-if="exerciseError.answer">{{ exerciseError.title }}</div>
                </div>

                <div class="mt-4">
                    <JetLabel for="answer" value="Odpowiedź"/>
                    <JetInput id="answer" v-model="exerciseForm.answer" type="text" placeholder="Odpowiedź"
                              class="block w-full" required/>
                    <div class="error-message" v-if="exerciseError.answer">{{ exerciseError.title }}</div>
                </div>

            </template>


            <template #footer>
                <JetButton
                    @click="() => hasOpenModal = false"
                    type="button"
                    class="bg-red-600 hover:bg-red-700 border-red-900 hover:border-red-600"
                >
                    <div class="flex items-center">
                        <box-icon name='left-arrow-circle' color="white"></box-icon>
                        <p class="ml-2 ">Zamknij</p>
                    </div>
                </JetButton>

                <JetButton
                    type="submit"
                    class="flex ml-2 items-center bg-[#4F46E5] hover:bg-[#2F26C5] border-[#2F26C5] hover:border-[#4F46E5] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10"
                >
                    <div class="flex items-center">
                        <p class="mr-2 ">Zapisz</p>
                        <box-icon name='plus-circle' color="white"></box-icon>
                    </div>
                </JetButton>
            </template>
        </DialogModal>
    </form>


    <DialogModal :show="hasOpenHistoryModal">
        <template #title>
            Historia wykonywania zadania
        </template>


        <template #content>
            <div class="overflow-x-auto rounded-2xl shadow">
                <table class="min-w-full border-collapse bg-white dark:bg-gray-900 text-sm">
                    <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-200">Wartość</th>
                        <th class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-200">Data</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(attempt, index) in attempts"
                        :key="index"
                        class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ index + 1 }}</td>
                        <td :class="'px-4 py-2 text-gray-800 ' + (attempt.is_correct == '0' ? 'text-red' : 'text-green')">
                            {{ attempt.value }}
                        </td>
                        <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ attempt.created_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>


        <template #footer>
            <JetButton
                @click="() => hasOpenHistoryModal = false"
                type="button"
                class="bg-red-600 hover:bg-red-700 border-red-900 hover:border-red-600"
            >
                <div class="flex items-center">
                    <box-icon name='left-arrow-circle' color="white"></box-icon>
                    <p class="ml-2 ">Zamknij</p>
                </div>
            </JetButton>
        </template>
    </DialogModal>
</template>
<style>
.text-red {
    color: red;
}

.text-green {
    color: green;
}
</style>
