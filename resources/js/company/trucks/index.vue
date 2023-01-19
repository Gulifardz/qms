<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Trucks" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Trucks</el-breadcrumb-item>
                            </el-breadcrumb>

                            <el-button class="text-white" color="#defaulte66460" size="small" @click="add()">Add Truck</el-button>
                        </div>

                        <div class="bg-white mt-4" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getTrucks()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto" v-loading="table.loading">
                                <!-- Brand -->
                                <el-table-column label="Brand">
                                    <template #default="scope">
                                        {{ scope.row.brand }}
                                    </template>
                                </el-table-column>

                                <!-- Year Model -->
                                <el-table-column label="Year Model">
                                    <template #default="scope">
                                        {{ scope.row.year_model }}
                                    </template>
                                </el-table-column>

                                <!-- Capacity -->
                                <el-table-column label="Capacity">
                                    <template #default="scope">
                                        {{ scope.row.capacity }} cubic
                                    </template>
                                </el-table-column>

                                <!-- Category -->
                                <el-table-column label="Category">
                                    <template #default="scope">
                                        {{ scope.row.truck_category.name }}
                                    </template>
                                </el-table-column>

                                <!-- ORCR -->
                                <el-table-column label="ORCR">
                                    <template #default="scope">
                                        {{ scope.row.orcr }}
                                    </template>
                                </el-table-column>

                                <!-- Plate No. -->
                                <el-table-column label="Plate No.">
                                    <template #default="scope">
                                        {{ scope.row.plate_no }}
                                    </template>
                                </el-table-column>

                                <!-- Drivers -->
                                <el-table-column label="Drivers">
                                    <template #default="scope">
                                        <el-tag type="success" v-for="(driver, index) in scope.row.drivers" :key="index" :class="{ 'me-1': index < (scope.row.drivers.length - 1) }">
                                            {{ driver.driver.name }}
                                        </el-tag>
                                    </template>
                                </el-table-column>

                                <!-- Action -->
                                <el-table-column label="Action">
                                    <template #default="scope">
                                        <el-button-group>
                                            <el-button type="default" size="small" @click="edit(scope.row.id)">
                                                <i class="fa fa-edit"></i>
                                            </el-button>
                                            <el-button type="default" size="small" @click="destroy(scope.row.id)">
                                                <i class="fa fa-trash"></i>
                                            </el-button>
                                        </el-button-group>
                                    </template>
                                </el-table-column>
                            </el-table>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end align-items-center p-4" v-if="table.data.length > 0">
                                <el-pagination class="custom" 
                                    layout="total, prev, pager, next"
                                    v-model:current-page="pagination.page"
                                    :page-size="pagination.per_page"
                                    :total="pagination.total"
                                    @current-change="getTrucks" 
                                    background 
                                    small
                                />
                            </div>
                        </div>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                table: {
                    data: [],
                    loading: true
                },
                filters: { 
                    search: ''
                },
                pagination: { 
                    total: 0, 
                    page: 1, 
                    per_page: 10 
                }
            }
        },
        mounted () {
            this.getTrucks();
        },
        methods: {
            getTrucks () {
                axios.post('/company/trucks/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { trucks, page, per_page, total } = response.data;

                    this.table.data = trucks;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;

                    this.table.loading = false;
                });
            },
            add () {
                window.location = '/company/trucks/add';
            },
            edit (id) {
                window.location = '/company/trucks/edit/' + id;
            },
            destroy (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Are you sure you want to delete this truck ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get('/company/trucks/destroy/' + id).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Truck has been deleted.');
                            this.getTrucks();
                        }).catch(error => {
                            const { status, errors } = error.response.data;

                            this.$root.showErrors({ status: status, errors: errors }, {}, {});
                        });
                    }
                });
            }
        }
    }
</script>