<script setup>
import { ref, nextTick } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';
import CategoryForm from './CategoryForm.vue';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    categories: Object
});

let search = defineModel();

let formModal = ref(null);
const formCat = (itemId, title) => {
    formModal.value.showModal(itemId, title);
}

const renderComponent = ref(true);
const reloadTable = async () => {
    renderComponent.value = false;
    await nextTick();
    renderComponent.value = true;
}

const deleteItem = (id) => {
    router.delete(route('category.destroy', {id: id}), {preserveScroll: true});
}

</script>

<template>
    <AppLayout title="Product Categories">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Product Categories</h1>
            <button type="button" class="btn btn-outline-light" @click="formCat(0, null)">Add</button>
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search category name" aria-label="RSearch Keyword" v-model="search">
            <Link :href="route('category.index')" :data="{ search }" preserve-state class="btn btn-outline-secondary">
                <font-awesome-icon :icon="['fas', 'magnifying-glass-arrow-right']" />
            </Link>
        </div>

        <div v-if="renderComponent" class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in categories.data" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.category_name }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="formCat(item.id, item.category_name)">
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
        <Pagination class="mt-5" :links="categories.links" />
        <CategoryForm ref="formModal" @posted="reloadTable"></CategoryForm>
    </AppLayout>
</template>
