<script setup>
import { onMounted, ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import Multiselect from '@vueform/multiselect';
import { useDropzone } from "vue3-dropzone";

let modalEle = ref(null);
let thisModalObj = null;

const emit = defineEmits(['posted']);

const form = useForm({
    product_code: '',
    product_name: '',
    categories: null,
    product_img: null,
});

onMounted(() => {
    thisModalObj = new Modal(modalEle.value);
});

let modalTitle = 'Add Product';
let productId = 0;
let tempCode = null;
let productImg = ref(null);

const showModal = (productData) => {
    if (productData !== undefined) {
        modalTitle = 'Edit Product';
        productId = productData.id;
        tempCode = productData.product_code;
        form.product_code = productData.product_code;
        form.product_name = productData.product_name;
        form.categories = productData.categoryIds;
        productImg.value = productData.thumbnail;
    }
    thisModalObj.show();
}

const closeModal = () => {
    productId = 0;
    tempCode = null;
    form.reset();
    thisModalObj.hide();
}

const getCatOptions = () => {
    return axios.get(route('catAjax'))
        .then(resp => {
            return resp.data
        })
}

defineExpose({ showModal: showModal });

const submitForm = () => {
    if (productId !== 0 ) {
        router.post(route('product.update', {id: productId}), {
            _method: 'put',
            forceFormData: true,
            product_code: form.product_code,
            product_name: form.product_name,
            categories: form.categories,
            product_img: form.product_img,
        }, {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    } else {
        form.post(route('product.store'), {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    }
}

const postedData = () => {
    emit('posted', true);
    closeModal();
}

const getCode = () => {
    $(".tooltip").remove();
    axios.get(route('codeGenerate'))
        .then(resp => {
            form.product_code = resp.data;
        })
}

let isUsed = ref(false);
let warnMsg = 'This code is already used!';
watch(() => form.product_code, (newVal) => {
    if (newVal !== null && newVal !== tempCode) {
        if (newVal.length == 8) {
            axios.get(route('codeCheck', {product_code: newVal}))
                .then(resp => {
                    isUsed.value = resp.data;
                })
        } else if (newVal.length < 8 || newVal.length > 8) {
            isUsed.value = !isUsed.value;
            warnMsg = 'Product code must be 8 digits number';
        }
    }
})

const onDrop = (acceptFiles, rejectReasons) => {
    // console.log(rejectReasons);
    const file = acceptFiles[0];
    productImg.value = URL.createObjectURL(file);
    form.product_img = acceptFiles[0];
}
const { getRootProps, getInputProps, ...rest } = useDropzone({
    onDrop: onDrop
});
</script>

<template>
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="modal fade" tabindex="-1" aria-hidden="true" ref="modalEle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <InputLabel for="code" value="Code" />
                            <div class="mt-1 input-group">
                                <TextInput
                                    id="code"
                                    v-model="form.product_code"
                                    class="form-control"
                                    required
                                />
                                <button class="btn btn-outline-secondary" @click="getCode" type="button">Generate</button>
                            </div>
                            <span v-if="isUsed" class="text-smaller text-warning">{{ warnMsg }}</span>
                            <InputError class="mt-1" :message="form.errors.product_code" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="title" value="Title" />
                            <TextInput
                                id="title"
                                v-model="form.product_name"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.product_name" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="categories" value="Categories" />
                            <Multiselect
                                id="categories"
                                v-model="form.categories"
                                :options="getCatOptions"
                                mode="tags"
                                class="mt-1 form-control p-0"
                                valueProp="id"
                                label="category_name"
                                searchable
                            ></Multiselect>
                        </div>
                        <div class="mb-3">
                            <InputLabel for="img" value="Image" />
                            <div class="d-flex justify-content-center align-items-center custom-dropzone mt-1" v-bind="getRootProps()">
                                <input v-bind="getInputProps()" accept="image/*" />
                                <div v-if="productImg !== null" class="text-center">
                                    <img class="prodImg" :src="productImg" />
                                </div>
                                <div v-else>
                                    <p>Drag 'n' drop an image here, or click to select image</p>
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
