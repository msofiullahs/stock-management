<script setup>
import { ref, nextTick, defineModel } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';
import ProductForm from './ProductForm.vue';
import axios from 'axios';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    products: Object
});

let search = defineModel();

let formModal = ref(null);
const formProduct = (itemId) => {
    if (itemId !== 0) {
        axios.get(route('product.edit', {id: itemId}))
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
    router.delete(route('product.destroy', {id: id}), {preserveScroll: true});
}
const userProps = usePage().props.auth.user.access_items.product;
</script>

<template>
    <AppLayout title="Products">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
            <button v-if="userProps.includes('add')" type="button" class="btn btn-outline-light" @click="formProduct(0)">Add</button>
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search product name or product code" aria-label="Search Keyword" v-model="search">
            <Link :href="route('product.index')" :data="{ search }" preserve-state class="btn btn-outline-secondary">
                <font-awesome-icon :icon="['fas', 'magnifying-glass-arrow-right']" />
            </Link>
        </div>

        <div v-if="renderComponent" class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Code</th>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in products.data" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td><img :src="item.thumbnail" width="20" height="20" /></td>
                        <td>{{ item.product_code }}</td>
                        <td>{{ item.product_name }}</td>
                        <td class="text-end">{{ item.available_stock }}</td>
                        <td class="text-center">
                            <button v-if="userProps.includes('edit')" type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="formProduct(item.id)">
                                <font-awesome-icon :icon="['far', 'pen-to-square']" />
                            </button>
                            <template v-if="userProps.includes('delete')">
                                <span class="text-secondary mx-1">&#9475;</span>
                                <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="deleteItem(item.id)">
                                    <font-awesome-icon :icon="['far', 'trash-can']" />
                                </button>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination class="mt-5" :links="products.links" />
        <ProductForm ref="formModal" @posted="reloadTable"></ProductForm>
    </AppLayout>
</template>
