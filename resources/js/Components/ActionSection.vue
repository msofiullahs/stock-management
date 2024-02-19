<script setup>
import SectionTitle from './SectionTitle.vue';
</script>

<template>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title"><slot name="title" /></h5>
            <h6 class="card-subtitle mb-2 text-body-secondary"><slot name="description" /></h6>
        </div>
        <div class="card-body">
            <slot name="content" />
        </div>
    </div>
</template>
