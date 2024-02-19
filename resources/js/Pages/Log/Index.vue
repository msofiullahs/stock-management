<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment-timezone';

const props = defineProps({
    logs: Object,
});

const formatDt = 'dd/MM/yyyy';
const date = ref();

onMounted(() => {
    const startDate = moment.tz(usePage().props.timezone).subtract(1, 'months');
    const endDate = moment.tz(usePage().props.timezone);
    date.value = [startDate, endDate];
})

const formatting = (val) => {
    return Intl.NumberFormat('id-ID', {style: 'currency', currency: usePage().props.currency}).format(val);
}

</script>

<template>
    <AppLayout title="Logs">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Stock Logs</h1>
        </div>

        <div class="row mb-3">
            <div class="col">
                <VueDatePicker
                    v-model="date"
                    placeholder="select date range"
                    dark
                    auto-apply
                    input-class-name="form-control"
                    :timezone="$inertia.page.props.timezone"
                    :readonly="isReadOnly"
                    :format="formatDt"
                    :enable-time-picker="false"
                    max-range="31"
                    :max-date="new Date()"
                    range />
            </div>
            <div class="col-auto">
                <Link :href="route('log')" :data="{ date }" preserve-state class="btn btn-outline-light">Generate</Link>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Stock</th>
                                <th>User</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in logs.data" :key="item.id">
                                <td>{{ moment.tz(item.created_at, $inertia.page.props.timezone).format('DD/MM/YYYY HH:mm:ss') }}</td>
                                <td class="text-center">{{ item.stock }}</td>
                                <td>{{ item.user.name }}</td>
                                <td class="text-end">{{ item.log_type }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Pagination class="mt-5" :links="logs.links" />
    </AppLayout>
</template>
