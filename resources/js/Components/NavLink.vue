<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'nav-link text-secondary-emphasis d-flex align-items-center gap-2 active'
        : 'nav-link text-info d-flex align-items-center gap-2';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
