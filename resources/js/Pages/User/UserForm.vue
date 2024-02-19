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
import Multiselect from '@vueform/multiselect';

library.add(
    faEye,
    faEyeSlash,
)

const props = defineProps({
    user: Object
});

const form = useForm({
    name: '',
    email: '',
    password: null,
    password_confirmation: null,
    role_id: '',
});

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

const getRoleOptions = () => {
    return axios.get(route('roles'))
        .then(resp => {
            return resp.data
        })
}

let roleInfo = [];
let onCheck = ref(false);
const getRoleInfo = (value, select$) => {
    roleInfo = [];
    onCheck.value = false;
    if (value !== null && value !== undefined) {
        axios.get(route('rolesInfo', {id: value}))
            .then(resp => {
                roleInfo = JSON.parse(resp.data.access_items);
                onCheck.value = true;
                console.log(roleInfo);
            })
    } else {
        roleInfo = [];
        onCheck.value = false;
    }
}

let pageTitle = 'Add User';
onMounted(() => {
    if (props.user) {
        pageTitle = 'Edit User';
        form.name = props.user.name;
        form.email= props.user.email;
        form.role_id= props.user.role_id;
    }
});

const userProps = usePage().props.auth.user.access_items;

const submitForm = () => {
    if (props.user ) {
        router.post(route('user.update', {id: props.user.id}), {
            _method: 'put',
            forceFormData: true,
            name: form.name,
            email: form.email,
            role_id: form.role_id
        }, {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    } else {
        form.post(route('user.store'), {
            onSuccess: () => {
                form.reset();
                postedData();
            }
        })
    }
}
</script>

<template>
    <AppLayout :title="pageTitle">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">{{ pageTitle }}</h1>
            <Link :href="route('user.index')" type="button" class="btn btn-outline-light">Back</Link>
        </div>

        <form @submit.prevent="submitForm">
            <div class="row mb-3">
                <InputLabel for="name" value="Name" class="col-sm-3 col-form-label" />
                <div class="col-sm-9">
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="form-control"

                    />
                    <InputError :message="form.errors.name" />
                </div>
            </div>
            <div class="row mb-3">
                <InputLabel for="email" value="Email" class="col-sm-3 col-form-label" />
                <div class="col-sm-9">
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="form-control"

                    />
                    <InputError :message="form.errors.email" />
                </div>
            </div>

            <div v-if="!user" class="mb-3 row">
                <InputLabel for="password" value="Password" class="col-sm-3 col-form-label" />
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

            <div v-if="!user" class="mb-3 row">
                <InputLabel for="password_confirmation" value="Confirmation" class="col-sm-3 col-form-label" />
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

            <div v-if="'user' in userProps" class="row mb-3">
                <InputLabel for="role" value="Role" class="col-sm-3 col-form-label" />
                <div class="col-sm-9">
                    <Multiselect
                        id="role"
                        v-model="form.role_id"
                        :options="getRoleOptions"
                        mode="single"
                        class="mt-1 form-control p-0"
                        valueProp="id"
                        label="role_name"
                        searchable
                        required
                        @change="getRoleInfo"
                    ></Multiselect>
                    <div v-if="onCheck" class="row mt-2">
                        <div v-for="(item, i) in roleInfo" :key="i" class="col">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item fw-bold text-uppercase">{{ i }}</li>
                                <li v-for="(acc, k) in item" :key="k" class="list-group-item fw-light">{{ acc }}</li>
                            </ul>
                        </div>
                    </div>
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
