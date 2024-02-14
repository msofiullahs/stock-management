<script setup>
import { onMounted, ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

let modalEle = ref(null);
let thisModalObj = null;

const emit = defineEmits(['posted']);

const form = useForm({
    supplier_code: '',
    supplier_name: '',
    supplier_phone: '',
    pic_name: null,
    pic_phone: null,
    address: null,
});

onMounted(() => {
    thisModalObj = new Modal(modalEle.value);
});

let modalTitle = 'Add Supplier';
let supplierId = 0;
let tempCode = null;

const showModal = (supplierData) => {
    if (supplierData !== undefined) {
        modalTitle = 'Edit Supplier';
        supplierId = supplierData.id;
        tempCode = supplierData.supplier_code;
        form.supplier_code = supplierData.supplier_code;
        form.supplier_name = supplierData.supplier_name;
        form.supplier_phone = supplierData.supplier_phone;
        form.pic_name = supplierData.pic_name;
        form.pic_phone = supplierData.pic_phone;
        form.address = supplierData.address;
    }
    thisModalObj.show();
}

const closeModal = () => {
    supplierId = 0;
    tempCode = null;
    form.reset();
    thisModalObj.hide();
}

defineExpose({ showModal: showModal });

const submitForm = () => {
    if (supplierId !== 0 ) {
        router.post(route('supplier.update', {id: supplierId}), {
            _method: 'put',
            forceFormData: true,
            supplier_code: form.supplier_code,
            supplier_name: form.supplier_name,
            supplier_phone: form.supplier_phone,
            pic_name: form.pic_name,
            pic_phone: form.pic_phone,
            address: form.address,
        }, {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    } else {
        form.post(route('supplier.store'), {
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
    axios.get(route('supplierGenerate'))
        .then(resp => {
            form.supplier_code = resp.data;
        })
}

let isUsed = ref(false);
let warnMsg = 'This code is already used!';
watch(() => form.supplier_code, (newVal) => {
    if (newVal !== null && newVal !== tempCode) {
        if (newVal.length == 8) {
            axios.get(route('supplierCheck', {supplier_code: newVal}))
                .then(resp => {
                    isUsed.value = resp.data;
                })
        } else if (newVal.length < 8 || newVal.length > 8) {
            isUsed.value = !isUsed.value;
            warnMsg = 'Supplier code must be 8 digits number';
        }
    }
});
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
                            <InputLabel for="code" value="Code" />
                            <div class="mt-1 input-group">
                                <TextInput
                                    id="code"
                                    v-model="form.supplier_code"
                                    class="form-control"
                                    required
                                />
                                <button class="btn btn-outline-secondary" @click="getCode" type="button">Generate</button>
                            </div>
                            <span v-if="isUsed" class="text-smaller text-warning">{{ warnMsg }}</span>
                            <InputError class="mt-1" :message="form.errors.supplier_code" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="title" value="Supplier" />
                            <TextInput
                                id="title"
                                v-model="form.supplier_name"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.supplier_name" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="phone" value="Phone" />
                            <TextInput
                                id="phone"
                                v-model="form.supplier_phone"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.supplier_phone" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="pic" value="PIC" />
                            <TextInput
                                id="pic"
                                v-model="form.pic_name"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.pic_name" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="picphone" value="PIC Phone" />
                            <TextInput
                                id="picphone"
                                v-model="form.pic_phone"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.pic_phone" />
                        </div>
                        <div class="mb-3">
                            <InputLabel for="address" value="Address" />
                            <textarea class="mt-1 form-control" id="address" v-model="form.address" required></textarea>
                            <InputError class="mt-1" :message="form.errors.address" />
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
