<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
// import Dropdown from '@/Components/Dropdown.vue';
// import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
// import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import {faBars, faHouse, faGauge, faArrowRightFromBracket, faBrain, faCubesStacked, faBox, faMoneyBill, faChartLine, faParachuteBox} from '@fortawesome/free-solid-svg-icons';
import {faObjectGroup, faCircleUser, faClock} from '@fortawesome/free-regular-svg-icons';

defineProps({
    title: String,
});

library.add(
    faBars,
    faHouse,
    faGauge,
    faArrowRightFromBracket,
    faBrain,
    faCubesStacked,
    faBox,
    faObjectGroup,
    faMoneyBill,
    faCircleUser,
    faChartLine,
    faParachuteBox,
    faClock,
);

// const showingNavigationDropdown = ref(false);

// const switchToTeam = (team) => {
//     router.put(route('current-team.update'), {
//         team_id: team.id,
//     }, {
//         preserveState: false,
//     });
// };

const mycopy = (valStr) => {
    return atob(valStr);
}

const logout = () => {
    router.post(route('logout'));
};

const userProps = usePage().props.auth.user;
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen">
            <header class="navbar sticky-top flex-md-nowrap p-0 shadow" data-bs-theme="dark">
                <Link :href="route('home')" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white">
                    <ApplicationMark class="block h-9 w-auto" />
                </Link>
                <div class="flex-row">
                    <div class="dropdown d-inline-flex">
                        <button class="nav-link px-3 text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $page.props.auth.user.name }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Manage Account</h6>
                            <Link class="dropdown-item" :href="route('user.edit', {id: $inertia.page.props.auth.user.id})">Profile</Link>
                            <Link class="dropdown-item" :href="route('user.editpassword')">Change Password</Link>
                            <Link class="dropdown-item" v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">API Tokens</Link>
                            <form @submit.prevent="logout">
                                <button href="#" class="dropdown-item" type="submit">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="d-inline-flex d-md-none">
                        <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                            <font-awesome-icon :icon="['fas', 'bars']" />
                        </button>
                    </div>
                </div>
            </header>
            <div class="container-fluid">
                <div class="row">
                    <div class="sidebar border border-right col-md-3 col-lg-2 p-0">
                        <div class="offcanvas-md offcanvas-end" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                            <div class="offcanvas-header">
                                <Link :href="route('home')" class="navbar-brand col-md-3 col-lg-2 me-0 fs-6 text-white">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <NavLink :href="route('home')" :active="route().current('home')">
                                            <font-awesome-icon :icon="['fas', 'gauge']" /> Dashboard
                                        </NavLink>
                                    </li>
                                    <li v-if="'category' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('category.index')" :active="route().current('category.*')">
                                            <font-awesome-icon :icon="['far', 'object-group']" /> Product Categories
                                        </NavLink>
                                    </li>
                                    <li v-if="'supplier' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('supplier.index')" :active="route().current('supplier.*')">
                                            <font-awesome-icon :icon="['fas', 'parachute-box']" /> Suppliers
                                        </NavLink>
                                    </li>
                                    <li v-if="'product' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('product.index')" :active="route().current('product.*')">
                                            <font-awesome-icon :icon="['fas', 'box']" /> Products
                                        </NavLink>
                                    </li>
                                    <li v-if="'price' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('price.index')" :active="route().current('price.*')">
                                            <font-awesome-icon :icon="['fas', 'money-bill']" /> Product Prices
                                        </NavLink>
                                    </li>
                                    <li v-if="'stock' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('stock.index')" :active="route().current('stock.*')">
                                            <font-awesome-icon :icon="['fas', 'cubes-stacked']" /> Product Stocks
                                        </NavLink>
                                    </li>
                                    <li v-if="'report' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('report')" :active="route().current('report')">
                                            <font-awesome-icon :icon="['fas', 'chart-line']" /> Report
                                        </NavLink>
                                    </li>
                                    <li v-if="'logs' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('log')" :active="route().current('log')">
                                            <font-awesome-icon :icon="['far', 'clock']" /> Stock Logs
                                        </NavLink>
                                    </li>
                                    <li v-if="'user' in userProps.access_items" class="nav-item">
                                        <NavLink :href="route('user.index')" :active="route().current('user.*')">
                                            <font-awesome-icon :icon="['far', 'circle-user']" /> Users
                                        </NavLink>
                                    </li>
                                </ul>
                                <hr class="my-3" />
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <form @submit.prevent="logout">
                                            <NavLink href="#" as="button">
                                                <font-awesome-icon :icon="['fas', 'arrow-right-from-bracket']" /> Sign out
                                            </NavLink>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                        <slot />
                        <div class="row align-items-center justify-content-between mt-5 mb-3 fw-lighter">
                            <div class="col">
                                <small>&copy; {{ new Date().getFullYear() }}</small>
                            </div>
                            <div class="col text-end">
                                <small>{{ mycopy('TWFpbiBBdXRob3JpdHk6IE11aGFtbWFkIFNvZml1bGxhaCBTLg==') }}</small>
                            </div>
                        </div>
                    </main>
                </div>
            </div>

            <!-- Page Heading -->
            <!-- <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header> -->

            <!-- Page Content -->
            <!-- <main>
                <slot />
            </main> -->
        </div>
    </div>
</template>
