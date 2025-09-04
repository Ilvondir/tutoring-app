<script setup>
import 'boxicons';
import JetButton from "@/Components/Button.vue";
</script>

<template>
    <a href="#" onclick="history.back();return false;">
        <JetButton
            type="button"
            class="bg-red-600 hover:bg-red-700 border-red-900 hover:border-red-600"
        >
            <div class="flex items-center">
                <box-icon name='left-arrow-circle' color="white"></box-icon>
                <p class="ml-2 ">Powrót</p>
            </div>
        </JetButton>
    </a>
</template>
