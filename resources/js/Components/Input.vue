<script setup>
import {onMounted, ref} from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input
        ref="input"
        class="focus:border-[#4F46E5] focus:ring-[#4F46E5] focus:ring-opacity-50 shadow-sm dark:bg-black bg-gray-200 text-black dark:text-gray-400"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
