<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type"
            class="inline-block text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10">
        <slot />
    </button>
</template>
