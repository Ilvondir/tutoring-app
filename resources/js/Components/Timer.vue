<script setup>
import JetButton from "@/Components/Button.vue";
import 'boxicons';
import {onMounted, ref, onUnmounted, computed} from "vue";
import {router} from "@inertiajs/vue3";
import Toast from "@/Components/Toast.vue";
import {useToast} from "vue-toastification";

const isStarted = ref(localStorage.getItem('timer_isStarted') === 'true')
const elapsed = ref(Number(localStorage.getItem('timer_elapsed') ?? 0));

const toast = useToast();

let intervalId = null

const handleClick = () => {
    if (isStarted.value) {
        router.post(route('learning-sessions.store'), {
            seconds: elapsed.value,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success({
                    component: Toast,
                    props: {
                        operation: 'Sesja nauki zapisana!'
                    }
                });
                elapsed.value = 0;
                localStorage.setItem('timer_elapsed', 0);
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

    isStarted.value = !isStarted.value
    localStorage.setItem('timer_isStarted', isStarted.value)
}

const tick = () => {
    if (isStarted.value) {
        elapsed.value++
        localStorage.setItem('timer_elapsed', elapsed.value)
    }
}

onMounted(() => {
    intervalId = setInterval(tick, 1000)
})

onUnmounted(() => {
    clearInterval(intervalId);
})

const formattedTime = computed(() => {
    const hours = String(Math.floor(elapsed.value / 3600)).padStart(2, '0')
    const minutes = String(Math.floor((elapsed.value % 3600) / 60)).padStart(2, '0')
    const seconds = String(elapsed.value % 60).padStart(2, '0')
    return `${hours}:${minutes}:${seconds}`
})
</script>

<template>
    <div class="h-100 flex items-center ml-8">
        {{ formattedTime }}

        <JetButton
            type="button"
            @click="handleClick"
            class="flex ml-2 items-center bg-[#4F46E5] hover:bg-[#2F26C5] border-[#2F26C5] hover:border-[#4F46E5] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10"
        >
            <div class="flex items-center">
                <p class="mr-2">{{ isStarted ? 'Koniec' : 'Start' }}</p>
                <box-icon name='time' color="white"></box-icon>
            </div>
        </JetButton>
    </div>
</template>
