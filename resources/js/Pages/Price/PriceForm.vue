<script setup>
import { onMounted, ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import Multiselect from '@vueform/multiselect';

let modalEle = ref(null);
let thisModalObj = null;

const emit = defineEmits(['posted']);

const form = useForm({
    product_id: null,
    price: null,
    price_type: null
});

onMounted(() => {
    thisModalObj = new Modal(modalEle.value);
});

let modalTitle = 'Add Product';
let priceId = 0;

const showModal = (priceData) => {
    if (priceData !== undefined) {
        console.log('PRICE', priceData);
        modalTitle = 'Edit Price';
        priceId = priceData.id;
        form.product_id = priceData.product_id;
        form.price = priceData.price;
        form.price_type = priceData.price_type;
    }
    thisModalObj.show();
}

const closeModal = () => {
    priceId = 0;
    form.reset();
    thisModalObj.hide();
}

const postedData = () => {
    emit('posted', true);
    closeModal();
}

const getProductOptions = () => {
    return axios.get(route('productAjax'))
        .then(resp => {
            return resp.data
        })
}

defineExpose({ showModal: showModal });

const submitForm = () => {
    if (priceId !== 0 ) {
        router.post(route('price.update', {id: priceId}), {
            _method: 'put',
            forceFormData: true,
            product_id: form.product_id,
            price: form.price,
            price_type: form.price_type,
        }, {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    } else {
        form.post(route('price.store'), {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    }
}

// const formatting = (e) => {
//     console.log(e.target.value);
//     const numbFormat = new Intl.NumberFormat('id-ID').format(e.target.value);
//     form.price = numbFormat;
// }
</script>

<template>
    <form @submit.prevent="submitForm">
        <div class="modal fade" tabindex="-1" aria-hidden="true" ref="modalEle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <InputLabel for="product" value="Product" />
                            <Multiselect
                                id="product"
                                v-model="form.product_id"
                                :options="getProductOptions"
                                mode="single"
                                class="mt-1 form-control p-0"
                                valueProp="id"
                                label="product_name"
                                searchable
                            ></Multiselect>
                        </div>
                        <div class="mb-3">
                            <InputLabel for="price" value="Price" />
                            <TextInput
                                type="text"
                                id="price"
                                v-model="form.price"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.price" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="pricetype" value="Type" />
                            <div class="row mt-1">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.price_type" id="in" value="in">
                                        <label class="form-check-label" for="in">
                                            In
                                        </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.price_type" id="out" value="out">
                                        <label class="form-check-label" for="out">
                                            Out
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-light" :disabled="form.processing">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
