<script setup>
import { ref, nextTick, defineModel } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';
import StockForm from './StockForm.vue';
import moment from 'moment-timezone';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    stocks: Object
});

// console.log(usePage().props);

let search = defineModel();

let formModal = ref(null);
const formStock = (itemId) => {
    if (itemId !== 0) {
        axios.get(route('stock.edit', {id: itemId}))
            .then(resp => {
                formModal.value.showModal(resp.data);
            });
    } else {
        formModal.value.showModal();
    }

}

const renderComponent = ref(true);
const reloadTable = async () => {
    renderComponent.value = false;
    await nextTick();
    renderComponent.value = true;
}

</script>

<template>
    <AppLayout title="Product Stocks">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Product Stocks</h1>
            <button type="button" class="btn btn-outline-light" @click="formStock(0)">Add</button>
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search product name or product code" aria-label="Search Keyword" v-model="search">
            <Link :href="route('stock.index')" :data="{ search }" preserve-state class="btn btn-outline-secondary">
                <font-awesome-icon :icon="['fas', 'magnifying-glass-arrow-right']" />
            </Link>
        </div>

        <div class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th>Price/item</th>
                        <th>Stock</th>
                        <th>Inserted</th>
                        <th>Modified</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in stocks.data" :key="item.id">
                        <td>{{ item.product.product_code+' - '+item.product.product_name }}</td>
                        <td>{{ item.supplier_id !== null ? item.supplier.supplier_name : '-' }}</td>
                        <td class="text-end">{{ item.price.curr_price }}</td>
                        <td class="text-end">{{ item.stock_type === 'out' ? '-'+item.numb_of_stock : item.numb_of_stock }}</td>
                        <td class="text-end">{{ moment.tz(item.created_at, $inertia.page.props.timezone).format('DD/MM/YYYY HH:mm:ss') }}</td>
                        <td class="text-end">{{ moment.tz(item.updated_at, $inertia.page.props.timezone).format('DD/MM/YYYY HH:mm:ss') }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="formStock(item.id)">
                                <font-awesome-icon :icon="['far', 'pen-to-square']" />
                            </button>
                            <!-- <span class="text-secondary mx-1">&#9475;</span>
                            <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="deleteItem(item.id)">
                                <font-awesome-icon :icon="['far', 'trash-can']" />
                            </button> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination class="mt-5" :links="stocks.links" />
        <StockForm ref="formModal" @posted="reloadTable"></StockForm>
    </AppLayout>
</template>
