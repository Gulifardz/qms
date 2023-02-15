<template>
    <el-aside class="dashboard__sidebar">
        <div class="text-center py-4">
            <i class="fa fa-building fa-3x fa-flip-horizontal text-white" aria-hidden="true"></i>
        </div>

        <!-- Admin Dashboard -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/dashboard" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Dashboard' }">
                <i class="fa fa-tachometer"></i>
                <span class="ms-3">Dashboard</span>
            </a>
        </div>

        <!-- Admin Quarries -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/quarries" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Quarries' }">
                <i class="fa fa-truck"></i>
                <span class="ms-3">Quarries</span>
            </a>
        </div>

        <!-- Admin Companies -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/companies" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Companies' }">
                <i class="fa fa-building"></i>
                <span class="ms-3">Companies</span>
            </a>
        </div>

        <!-- Admin Truck Categories -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/truck-categories" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Truck Categories' }">
                <i class="fa fa-list"></i>
                <span class="ms-3">Categories</span>
            </a>
        </div>
        
        <!-- Admin Products -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/products" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Products' }">
                <i class="fa fa-list"></i>
                <span class="ms-3">Products</span>
            </a>
        </div>

        <!-- Admin Checkers -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/checkers" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Checkers' }">
                <i class="fa fa-users"></i>
                <span class="ms-3">Checkers</span>
            </a>
        </div>

        <!-- Admin Supermities -->
        <div class="nav-item" v-if="$root.user.type === 'Admin'">
            <a href="/admin/supermities" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Supermities' }">
                <i class="fa fa-users"></i>
                <span class="ms-3">Supermities</span>
            </a>
        </div>





        <!-- Company Drivers -->
        <div class="nav-item" v-if="$root.user.type === 'Company'">
            <a href="/company/drivers" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Drivers' }">
                <i class="fa fa-users"></i>
                <span class="ms-3">Drivers</span>
            </a>
        </div>

        <!-- Company Trucks -->
        <div class="nav-item" v-if="$root.user.type === 'Company'">
            <a href="/company/trucks" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Trucks' }">
                <i class="fa fa-truck"></i>
                <span class="ms-3">Trucks</span>
            </a>
        </div>

        <!-- Company Logs -->
        <div class="nav-item" v-if="$root.user.type === 'Company'">
            <a href="/company/logs" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Logs' }">
                <i class="fa fa-book"></i>
                <span class="ms-3">Logs</span>
            </a>
        </div>





        <!-- Quarry Products -->
        <div class="nav-item" v-if="$root.user.type === 'Quarry'">
            <a href="/quarry/products" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Products' }">
                <i class="fa fa-list"></i>
                <span class="ms-3">Products</span>
            </a>
        </div>

        <!-- Quarry Checkers -->
        <div class="nav-item" v-if="$root.user.type === 'Quarry'">
            <a href="/quarry/checkers" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Checkers' }">
                <i class="fa fa-users"></i>
                <span class="ms-3">Checkers</span>
            </a>
        </div>

        <!-- Quarry Supermities -->
        <div class="nav-item" v-if="$root.user.type === 'Quarry'">
            <a href="/quarry/supermities" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Supermities' }">
                <i class="fa fa-users"></i>
                <span class="ms-3">Supermities</span>
            </a>
        </div>

        <!-- Quarry Logs -->
        <div class="nav-item" v-if="$root.user.type === 'Quarry'">
            <a href="/quarry/logs" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Logs' }">
                <i class="fa fa-history"></i>
                <span class="ms-3">Logs</span>
            </a>
        </div>




        <!-- Supermity Logs -->
        <div class="nav-item" v-if="$root.user.type === 'Supermity'">
            <a href="/supermity/logs" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Logs' }">
                <i class="fa fa-history"></i>
                <span class="ms-3">Logs</span>
            </a>
        </div>




        <!-- Checker Logs -->
        <div class="nav-item" v-if="$root.user.type === 'Checker'">
            <a href="/checker/logs" class="px-5 py-3 nav-link" :class="{ 'active': page === 'Logs' }">
                <i class="fa fa-history"></i>
                <span class="ms-3">Logs</span>
            </a>
        </div>
    </el-aside>
</template>

<script>
    export default {
        data () {
            return {
            }
        },
        props: ['page']
    }
</script>