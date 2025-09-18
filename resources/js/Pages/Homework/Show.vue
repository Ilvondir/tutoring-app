<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, ref, watch} from "vue";
import JetButton from "@/Components/Button.vue";
import JetLabel from "@/Components/Label.vue";
import JetInput from "@/Components/Input.vue";
import 'boxicons';
import ReturnButton from "@/Components/ReturnButton.vue";
import Toast from "@/Components/Toast.vue";
import {useToast} from "vue-toastification";
import TextArea from "@/Components/TextArea.vue";
import ConfettiExplosion from "vue-confetti-explosion"
import Fireworks from "@fireworks-js/vue";

const props = defineProps({
    item: Object,
    errors: Object,
});

const toast = useToast();
const explodingTriggers = ref([]);
let correctExerciseAudio, finishHomeworkAudio;
const fw = ref();
const fireworksActive = ref(false);
const options = ref({
    opacity: 0.5,
    sound: {
        explosion: 100,
        enabled: true,
        files: [
            '/explosion0.mp3',
            '/explosion1.mp3',
            '/explosion2.mp3',
        ],
        volume: {
            min: 100,
            max: 100,
        }
    },
})

let form = useForm({
    id: props.item.id,
    title: props.item.title,
});

const checkExercise = (id, order) => {
    const answer = document.querySelector('#answer-' + id);

    router.patch(route('exercises.check', id), {
        answer: answer.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({
                component: Toast,
                props: {
                    operation: 'Operacja zakończona powodzeniem!'
                }
            });
            
            if (props.item.complete_date) {
                finishHomeworkAudio.play();
                fireworksActive.value = true;
                fw.value.start();
                setTimeout(() => {
                    fw.value.stop();
                    fireworksActive.value = false;
                }, 15000);
            } else {
                explodingTriggers.value[order] = true;
                correctExerciseAudio.play();
            }
        },
        onError: () => {
            toast.error({
                component: Toast,
                props: {
                    operation: 'Odpowiedź jest niepoprawna.'
                }
            });
            answer.value = '';
        },
    })
}

onMounted(() => {
    props.item.exercises.forEach(() => {
        explodingTriggers.value.push(false);
    });

    correctExerciseAudio = new Audio("/success.mp3");
    correctExerciseAudio.preload = "auto";

    finishHomeworkAudio = new Audio('/applauz.mp3');
    finishHomeworkAudio.preload = 'auto';
})
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

        <Fireworks
            ref="fw"
            :autostart="false"
            v-show="fireworksActive"
            :options="options"
            :style="{
              top: 0,
              left: 0,
              width: '100%',
              height: '100%',
              position: 'fixed'
            }"
        />

        <div class="pt-12 pb-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 dark:text-gray-400 text-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                <div class="flex flex-col">
                    <div class="p-6">

                        <div>
                            <JetLabel for="title" value="Tytuł"/>
                            <JetInput id="title" v-model="form.title" type="text" placeholder="Tytuł"
                                      class="block w-full" disabled/>
                            <div class="error-message" v-if="errors.title">{{ errors.title }}</div>
                        </div>

                        <div class="flex justify-end mt-5">
                            <ReturnButton/>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="pb-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 dark:text-gray-400 text-gray-800 overflow-hidden shadow-xl sm:rounded-lg py-4 px-4">
                <div class="flex flex-col">
                    <div class="p-6">

                        <h1 class="text-2xl mb-3 font-bold">Status</h1>

                        <template v-if="props.item.complete_date">
                            <div class="text-[#14b069]">
                                Praca domowa została przez Ciebie ukończona {{ props.item.complete_date }}.
                            </div>
                        </template>
                        <template v-else>
                            <div class="text-red">
                                Praca domowa nie została jeszcze ukończona.
                            </div>
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
                        </div>


                        <template v-for="(exercise, index) in props.item.exercises">
                            <div class="flex items-center mt-5">
                                <h1 class="text-xl mb-1">Zadanie {{ exercise.order }}</h1>
                            </div>


                            <hr/>
                            <TextArea style="width: 100%" class="mt-2" v-model="exercise.assignment"
                                      disabled></TextArea>

                            <div class="text-center flex justify-center">
                                <ConfettiExplosion v-if="explodingTriggers[index]"
                                                   @complete="explodingTriggers[index] = false"/>
                            </div>


                            <template v-if="exercise.complete_date">
                                <JetInput :id="'answer-' + exercise.id" :value="exercise.answer" type="text"
                                          placeholder="Odpowiedź"
                                          class="block w-full" disabled/>
                            </template>
                            <template v-else>
                                <form @submit.prevent="() => checkExercise(exercise.id, index)">
                                    <div class="flex justify-between items-center">
                                        <JetInput :id="'answer-' + exercise.id" type="text"
                                                  placeholder="Odpowiedź"
                                                  class="block w-full" required/>

                                        <JetButton
                                            type="submit"
                                            class="flex ml-2 items-center bg-[#4F46E5] hover:bg-[#2F26C5] border-[#2F26C5] hover:border-[#4F46E5] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10"
                                        >
                                            <div class="flex items-center">
                                                <p class="mr-2">Sprawdź</p>
                                                <box-icon name='pen' color="white"></box-icon>
                                            </div>
                                        </JetButton>
                                    </div>

                                </form>

                            </template>

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

</template>
<style>
.text-red {
    color: red;
}
</style>
