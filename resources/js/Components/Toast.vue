<script setup>

const emit = defineEmits(['closeToast']);

const props = defineProps({
    operation: String,
});
</script>

<template>
    <p>
        {{ operation }}
    </p>
</template>

<style scoped>
p {
    margin: 0;
}
</style>
