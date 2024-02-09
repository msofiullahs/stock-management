<script setup>
import { ref, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';
import ProductForm from './ProductForm.vue';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    products: Object
});

console.log('products', props.products);

let formModal = ref(null);
const formProduct = (itemId, title) => {
    formModal.value.showModal(itemId, title);
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

</script>

<template>
    <AppLayout title="Products">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
            <button type="button" class="btn btn-outline-light" @click="formProduct(0, null)">Add</button>
        </div>

        <div v-if="renderComponent" class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Code</th>
                        <th>Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in products.data" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td><img :src="item.thumbnail" width="20" height="20" /></td>
                        <td>{{ item.product_code }}</td>
                        <td>{{ item.product_name }}</td>
                        <td>
                            <!-- <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="formCat(item.id, item.category_name)">
                                <font-awesome-icon :icon="['far', 'pen-to-square']" />
                            </button>
                            <span class="text-secondary mx-1">&#9475;</span>
                            <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="deleteItem(item.id)">
                                <font-awesome-icon :icon="['far', 'trash-can']" />
                            </button> -->
                            test
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination class="mt-5" :links="products.links" />
        <ProductForm ref="formModal" @posted="reloadTable"></ProductForm>
    </AppLayout>
</template>
