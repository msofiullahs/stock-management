<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlassArrowRight } from '@fortawesome/free-solid-svg-icons';
import { faPenToSquare, faTrashCan, faEye } from '@fortawesome/free-regular-svg-icons';

library.add(
    faMagnifyingGlassArrowRight,
    faPenToSquare,
    faTrashCan,
    faEye
)

const props = defineProps({
    categories: Object
});

</script>

<template>
    <AppLayout title="Product Categories">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Product Categories</h1>
            <!-- <div>
                <button type="button" class="btn btn-outline-light me-2" :disabled="disableButton" @click="multipleDelete">Delete</button>
                <Link :href="route('dash.content.article.create')" class="btn btn-outline-light">New Article</Link>
            </div> -->
        </div>

        <div class="table-responsive small">
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
                        <td>TEST</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination class="mt-5" :links="categories.links" />
    </AppLayout>
</template>
