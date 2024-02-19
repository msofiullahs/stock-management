<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEye, faEyeSlash } from '@fortawesome/free-regular-svg-icons';

library.add(
    faEye,
    faEyeSlash,
)

const form = useForm({
    old_password: null,
    password: null,
    password_confirmation: null,
});

let seeOld = ref(false);
const oldToggle = () => {
    seeOld.value = !seeOld.value;
}

let seePassword = ref(false);
const passwordToggle = () => {
    seePassword.value = !seePassword.value;
}

let seeConfirm = ref(false);
const confirmToggle = () => {
    seeConfirm.value = !seeConfirm.value;
}

let notEqual = ref(false);
const checkConfirm = () => {
    const ori = form.password;
    const comp = form.password_confirmation;
    notEqual = comp !== ori;
}

const submitForm = () => {
    form.post(route('user.updatepass'), {
        onSuccess: () => {
            form.reset();
            postedData();
        }
    })
}
</script>

<template>
    <AppLayout title="Change Password">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Change Password</h1>
        </div>
        <form @submit.prevent="submitForm">
            <div class="mb-3 row">
                <InputLabel for="curr_password" value="Current Password" class="col-sm-3 col-form-label" />
                <div class="col-sm-9">
                    <div class="input-group">
                        <TextInput
                            v-if="seeOld"
                            id="passcurr_passwordword"
                            v-model="form.old_password"
                            type="text"
                            class="form-control"
                            required
                            autocomplete="new-password"
                        />
                        <TextInput
                            v-else
                            id="curr_password"
                            v-model="form.old_password"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="new-password"
                        />
                        <button class="btn btn-outline-secondary" type="button" @click="oldToggle">
                            <font-awesome-icon :icon="['far', seeOld ? 'eye-slash' : 'eye']" />
                        </button>
                    </div>
                    <InputError :message="form.errors.old_password" />
                </div>
            </div>
            <div class="mb-3 row">
                <InputLabel for="password" value="New Password" class="col-sm-3 col-form-label" />
                <div class="col-sm-9">
                    <div class="input-group">
                        <TextInput
                            v-if="seePassword"
                            id="password"
                            v-model="form.password"
                            type="text"
                            class="form-control"
                            required
                            autocomplete="new-password"
                        />
                        <TextInput
                            v-else
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="form-control"
                            required
                            autocomplete="new-password"
                        />
                        <button class="btn btn-outline-secondary" type="button" @click="passwordToggle">
                            <font-awesome-icon :icon="['far', seePassword ? 'eye-slash' : 'eye']" />
                        </button>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>
            </div>

            <div class="mb-3 row">
                <InputLabel for="password_confirmation" value="New Confirmation" class="col-sm-3 col-form-label" />
                <div class="col-sm-9">
                    <div class="input-group">
                        <TextInput
                            v-if="seeConfirm"
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="text"
                            class="form-control"
                            @update:model-value="checkConfirm"
                            required
                            autocomplete="new-password"
                        />
                        <TextInput
                            v-else
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="form-control"
                            @update:model-value="checkConfirm"
                            required
                            autocomplete="new-password"
                        />
                        <button class="btn btn-outline-secondary" type="button" @click="confirmToggle">
                            <font-awesome-icon :icon="['far', seeConfirm ? 'eye-slash' : 'eye']" />
                        </button>
                    </div>
                    <span v-if="notEqual" class="text-warning" style="font-size: .75rem;">Confirmation password is not same with password!</span>
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="row">
                <div class="col text-end">
                    <button type="submit" class="btn btn-outline-light" :disabled="form.processing">Submit</button>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
