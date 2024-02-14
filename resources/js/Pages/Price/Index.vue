<script setup>
import { ref, nextTick, defineModel } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';
import axios from 'axios';
import PriceForm from './PriceForm.vue';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    prices: Object
});

let search = defineModel();

let formModal = ref(null);
const formPrice = (itemId) => {
    if (itemId !== 0) {
        axios.get(route('price.edit', {id: itemId}))
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

const deleteItem = (id) => {
    router.delete(route('price.destroy', {id: id}), {preserveScroll: true});
}

</script>

<template>
    <AppLayout title="Product Prices">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Product Prices</h1>
            <button type="button" class="btn btn-outline-light" @click="formPrice(0)">Add</button>
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search product name or product code" aria-label="RSearch Keyword" v-model="search">
            <Link :href="route('price.index')" :data="{ search }" preserve-state class="btn btn-outline-secondary">
                <font-awesome-icon :icon="['fas', 'magnifying-glass-arrow-right']" />
            </Link>
        </div>

        <div v-if="renderComponent" class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in prices.data" :key="item.id">
                        <td>{{ item.product.product_code }}</td>
                        <td>{{ item.product.product_name }}</td>
                        <td class="text-end">{{ item.curr_price }}</td>
                        <td class="text-center">{{ item.price_type }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="formPrice(item.id)">
                                <font-awesome-icon :icon="['far', 'pen-to-square']" />
                            </button>
                            <span class="text-secondary mx-1">&#9475;</span>
                            <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="deleteItem(item.id)">
                                <font-awesome-icon :icon="['far', 'trash-can']" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination class="mt-5" :links="prices.links" />
        <PriceForm ref="formModal" @posted="reloadTable"></PriceForm>
    </AppLayout>
</template>
