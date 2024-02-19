<script setup>
import { defineModel } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan } from '@fortawesome/free-regular-svg-icons';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan
)

const props = defineProps({
    users: Object
});

let search = defineModel();

const deleteItem = (id) => {
    router.delete(route('user.destroy', {id: id}), {preserveScroll: true});
}
const userProps = usePage().props.auth.user.access_items.user;

</script>

<template>
    <AppLayout title="Users">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Users</h1>
            <Link v-if="userProps.includes('add')" :href="route('user.create')" type="button" class="btn btn-outline-light">Add</Link>
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search user name or email" aria-label="Search Keyword" v-model="search">
            <Link :href="route('user.index')" :data="{ search }" preserve-state class="btn btn-outline-secondary">
                <font-awesome-icon :icon="['fas', 'magnifying-glass-arrow-right']" />
            </Link>
        </div>

        <div class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in users.data" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.role.role_name }}</td>
                        <td class="text-center">
                            <Link v-if="userProps.includes('edit')" class="btn btn-sm p-0" style="line-height: 1;" :href="route('user.edit', {id: item.id})">
                                <font-awesome-icon :icon="['far', 'pen-to-square']" />
                            </Link>
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
        <Pagination class="mt-5" :links="users.links" />
    </AppLayout>
</template>
