<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import Multiselect from '@vueform/multiselect';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

let modalEle = ref(null);
let thisModalObj = null;

const emit = defineEmits(['posted']);

const form = useForm({
    product_id: null,
    supplier_id: null,
    price_id: null,
    numb_of_stock: null,
    stock_type: null,
    stock_note: null,
    expired_at: null,
});

const formatDt = 'yyyy-MM-dd HH:mm:ss'
const onCheck = ref(false);
let priceOptions = [];
let stockType = null;
let productId = null;
const isRequired = ref(false);

onMounted(() => {
    thisModalObj = new Modal(modalEle.value);
});

let modalTitle = 'Add Stock History';
let stockId = 0;

const showModal = (stockData) => {
    if (stockData !== undefined) {
        modalTitle = 'Edit Stock History';
        stockId = stockData.id;
        form.product_id = stockData.product_id;
        form.supplier_id = stockData.supplier_id;
        form.price_id = stockData.price_id;
        form.numb_of_stock = stockData.numb_of_stock;
        form.stock_type = stockData.stock_type;
        form.stock_note = stockData.stock_note;
        form.expired_at = stockData.expired_at;
    }
    thisModalObj.show();
}

const closeModal = () => {
    stockId = 0;
    form.reset();
    priceOptions = [];
    productId = null;
    stockType = null;
    onCheck.value = false;
    thisModalObj.hide();
}

const getProductOptions = () => {
    return axios.get(route('productAjax'))
        .then(resp => {
            return resp.data
        })
}

const getSuppOptions = () => {
    return axios.get(route('supplierAjax'))
        .then(resp => {
            return resp.data
        })
}

const getPrices = (value, select$) => {
    priceOptions = [];
    onCheck.value = false;
    if (value !== null && value !== undefined) {
        productId = value;
        if (stockType !== null) {
            axios.get(route('priceAjax', {product_id: value, type: stockType}))
                .then(resp => {
                    priceOptions = resp.data;
                    onCheck.value = true;
                })
        }
    } else {
        priceOptions = [];
        productId = null;
        onCheck.value = false;
    }
}

watch(() => form.stock_type, (newVal) => {
    stockType = newVal;
    priceOptions = [];
    onCheck.value = false;
    isRequired.value = false;
    if (newVal !== null && productId !== null) {
        axios.get(route('priceAjax', {product_id: productId, type: newVal}))
                .then(resp => {
                    priceOptions = resp.data;
                    onCheck.value = true;
                })
    }

    if (newVal === 'in') {
        isRequired.value = true;
    }
})

defineExpose({ showModal: showModal });

const submitForm = () => {
    if (stockId !== 0 ) {
        router.post(route('stock.update', {id: stockId}), {
            _method: 'put',
            forceFormData: true,
            product_id: form.product_id,
            supplier_id: form.supplier_id,
            price_id: form.price_id,
            stock_type: form.stock_type,
            stock_note: form.stock_note,
            expired_at: form.expired_at,
        }, {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    } else {
        form.post(route('stock.store'), {
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
                                label="label"
                                searchable
                                required
                                @change="getPrices"
                            ></Multiselect>
                        </div>
                        <div class="mb-3">
                            <InputLabel for="stock_numb" value="Number of Stock" />
                            <TextInput
                                id="stock_numb"
                                v-model="form.numb_of_stock"
                                class="mt-1 form-control"
                                type="number"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.numb_of_stock" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="pricetype" value="Type" />
                            <div class="row mt-1">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.stock_type" id="in" value="in" required>
                                        <label class="form-check-label" for="in">
                                            In
                                        </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.stock_type" id="out" value="out">
                                        <label class="form-check-label" for="out">
                                            Out
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="onCheck" class="mb-3">
                            <InputLabel for="price" value="Stock Price" class="mb-1" />
                            <div v-for="price in priceOptions" :key="price.id" class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.price_id" :id="price.price_type+price.id" :value="price.id" required>
                                <label class="form-check-label" :for="price.price_type+price.id">
                                    {{ price.curr_price }}
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <InputLabel for="supplier" value="Supplier" />
                            <Multiselect
                                id="supplier"
                                v-model="form.supplier_id"
                                :options="getSuppOptions"
                                mode="single"
                                class="mt-1 form-control p-0"
                                valueProp="id"
                                label="supplier_name"
                                searchable
                                :required="isRequired"
                            ></Multiselect>
                        </div>
                        <div class="mb-3">
                            <InputLabel for="date" value="Expired at (optional)" />
                            <VueDatePicker
                                id="date"
                                v-model="form.expired_at"
                                :format="formatDt"
                                dark
                                auto-apply
                                input-class-name="mt-1 form-control"
                                :timezone="$inertia.page.props.timezone"
                                :readonly="isReadOnly">
                            </VueDatePicker>
                            <InputError class="mt-1" :message="form.errors.numb_of_stock" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="note" value="Note (optional)" />
                            <textarea class="form-control mt-1" v-model="form.stock_note"></textarea>
                            <InputError class="mt-1" :message="form.errors.stock_note" />
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
