<script setup>
import { onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

let modalEle = ref(null);
let thisModalObj = null;

const emit = defineEmits(['posted']);

const form = useForm({
    category_name: ''
});

onMounted(() => {
    thisModalObj = new Modal(modalEle.value);
});

let modalTitle = 'Add Category';
let itemId = 0;
const showModal = (catId, title) => {
    if (catId !== 0) {
        modalTitle = 'Edit Category';
        form.category_name = title;
        itemId = catId;
    }
    thisModalObj.show();
}

defineExpose({ showModal: showModal });

const submitForm = () => {
    form.post(route('category.store'), {
        onSuccess: () => {
            form.reset();
            postedData();
        }
    })
}

const postedData = () => {
    emit('posted', true);
    form.reset();
    thisModalObj.hide();
}

</script>

<template>
    <form @submit.prevent="submitForm">
        <div class="modal fade" tabindex="-1" aria-hidden="true" ref="modalEle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <InputLabel for="title" value="Title" />
                            <TextInput
                                id="title"
                                v-model="form.category_name"
                                class="mt-1 form-control"
                                required
                            />
                            <InputError class="mt-1" :message="form.errors.title" />
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
