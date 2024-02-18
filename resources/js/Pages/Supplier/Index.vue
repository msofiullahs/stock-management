<script setup>
import { ref, nextTick, defineModel } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';
import axios from 'axios';
import SupplierForm from './SupplierForm.vue';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    suppliers: Object
});

let search = defineModel();

let formModal = ref(null);
const formSupplier = (itemId) => {
    if (itemId !== 0) {
        axios.get(route('supplier.edit', {id: itemId}))
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
    router.delete(route('supplier.destroy', {id: id}), {preserveScroll: true});
}

const userProps = usePage().props.auth.user.access_items.supplier;

</script>

<template>
    <AppLayout title="Suppliers">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Suppliers</h1>
            <button v-if="userProps.includes('add')" type="button" class="btn btn-outline-light" @click="formSupplier(0)">Add</button>
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search supplier name or supplier code" aria-label="Search Keyword" v-model="search">
            <Link :href="route('supplier.index')" :data="{ search }" preserve-state class="btn btn-outline-secondary">
                <font-awesome-icon :icon="['fas', 'magnifying-glass-arrow-right']" />
            </Link>
        </div>

        <div v-if="renderComponent" class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Supplier</th>
                        <th>Phone</th>
                        <th>PIC</th>
                        <th>PIC Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in suppliers.data" :key="item.id">
                        <td>{{ item.supplier_code }}</td>
                        <td>{{ item.supplier_name }}</td>
                        <td>{{ item.supplier_phone }}</td>
                        <td>{{ item.pic_name }}</td>
                        <td>{{ item.pic_phone }}</td>
                        <td class="text-center">
                            <button v-if="userProps.includes('edit')" type="button" class="btn btn-sm p-0" style="line-height: 1;" @click="formSupplier(item.id)">
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
        <Pagination class="mt-5" :links="suppliers.links" />
        <SupplierForm ref="formModal" @posted="reloadTable"></SupplierForm>
    </AppLayout>
</template>
