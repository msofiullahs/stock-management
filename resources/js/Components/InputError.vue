<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="text-danger m-0 fw-light text-smaller">
            {{ message }}
        </p>
    </div>
</template>
