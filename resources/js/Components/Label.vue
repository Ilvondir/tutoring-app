<script setup>
import {onMounted, ref} from "vue";

defineProps({
    value: String,
});

const labelRef = ref(null);
const isRequired = ref(false);

onMounted(() => {
    isRequired.value = document.querySelector('#' + labelRef.value.getAttribute('for')).hasAttribute('required');
})
</script>

<template>
    <label ref="labelRef" class="block font-medium text-sm dark:text-gray-400 text-gray-800">
        <span v-if="value">{{ value }}</span>&nbsp;<span v-if="isRequired" class="text-red-600 font-bold">*</span>
        <span v-else><slot/></span>
    </label>
</template>
