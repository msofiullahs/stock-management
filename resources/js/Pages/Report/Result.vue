<script setup>
import { ref, nextTick } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import moment from 'moment-timezone';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faPrint } from '@fortawesome/free-solid-svg-icons';

library.add(
    faPrint,
)

const props = defineProps({
    reports: Object,
});

const formatDt = 'dd/MM/yyyy';
const date = ref();

const formatting = (val) => {
    return Intl.NumberFormat('id-ID', {style: 'currency', currency: usePage().props.currency}).format(val);
}
</script>

<template>
    <Head :title="'Report-' + moment.tz().format('DDMMYYYY-HHmmss')" />
    <div class="fullBody">
        <button type="button" class="btn btn-sm btn-default noPrint" onclick="window.print();"><font-awesome-icon :icon="['fas', 'print']" /></button>
        <table class="printTable" cellpadding="5">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>In/Out</th>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Item Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in reports" :key="item.id">
                    <td>{{ moment.tz(item.created_at, $inertia.page.props.timezone).format('DD/MM/YYYY HH:mm:ss') }}</td>
                    <td class="text-center">{{ item.stock_type }}</td>
                    <td>{{ item.product.product_code +' - '+ item.product.product_name }}</td>
                    <td class="text-end">{{ item.numb_of_stock }}</td>
                    <td class="text-end">{{ formatting(item.price.price) }}</td>
                    <td class="text-end">{{ formatting(item.price.price * item.numb_of_stock) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.fullBody{
    width: 100%;
    height: auto;
    min-height: 100vh;
    background: white;
    color: rgba(33,37,41,.75);
    margin: auto;
    font-size: .8rem;
}
.btn {
    color: rgba(33,37,41,.75);
}
.printTable{
    width: 100%;
}
.printTable tr td, .printTable tr th {
    border: 1px solid rgba(33,37,41,.75);
}
@media print {
    .noPrint{
        display:none;
    }
}
</style>
