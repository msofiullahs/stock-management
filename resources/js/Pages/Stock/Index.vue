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
    stocks: Object
});

</script>

<template>
    <AppLayout title="Product Stocks">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Product Stocks</h1>
            <!-- <div>
                <button type="button" class="btn btn-outline-light me-2" :disabled="disableButton" @click="multipleDelete">Delete</button>
                <Link :href="route('dash.content.article.create')" class="btn btn-outline-light">New Article</Link>
            </div> -->
        </div>

        <div class="table-responsive small">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>In/Out</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in stocks.data" :key="item.id">
                        <td>TEST</td>
                        <td>TEST</td>
                        <td>TEST</td>
                        <td>TEST</td>
                        <td>TEST</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination class="mt-5" :links="stocks.links" />
    </AppLayout>
</template>
