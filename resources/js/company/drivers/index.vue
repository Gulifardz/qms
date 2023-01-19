<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Drivers" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Drivers</el-breadcrumb-item>
                            </el-breadcrumb>

                            <el-button class="text-white" color="#e66460" size="small" @click="$root.redirect('/company/drivers/add')">Add Driver</el-button>
                        </div>

                        <div class="bg-white mt-4" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getDrivers()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Picture -->
                                <el-table-column label="Picture">
                                    <template #default="scope">
                                        <img :src="scope.row.picture + '?' + now" class="border driver-image" />
                                        
                                    </template>
                                </el-table-column>

                                <!-- Name -->
                                <el-table-column label="Name">
                                    <template #default="scope">
                                        {{ scope.row.name }}
                                    </template>
                                </el-table-column>

                                <!-- License No. -->
                                <el-table-column label="License No.">
                                    <template #default="scope">
                                        {{ scope.row.license_no }}
                                    </template>
                                </el-table-column>

                                <!-- Contact No. -->
                                <el-table-column label="Contact No.">
                                    <template #default="scope">
                                        {{ scope.row.contact_no }}
                                    </template>
                                </el-table-column>

                                <!-- Action -->
                                <el-table-column label="Action">
                                    <template #default="scope">
                                        <el-button-group>
                                            <el-button type="default" size="small" @click="$root.redirect(`/company/drivers/qr/${ scope.row.id }`)">
                                                <i class="fa fa-qrcode"></i>
                                            </el-button>
                                            <el-button type="default" size="small" @click="$root.redirect(`/company/drivers/edit/${ scope.row.id }`)">
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
                                    @current-change="getDrivers" 
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
                    data: []
                },
                filters: { 
                    search: ''
                },
                pagination: { 
                    total: 0, 
                    page: 1, 
                    per_page: 10 
                },
                form: {
                    labels: {
                        name: 'Name'
                    },
                    errors: {
                        name: ''
                    },
                    inputs: {
                        id: '',
                        name: ''
                    },
                    loading: false
                },
                now: Math.floor(Date.now() / 1000)
            }
        },
        mounted () {
            this.getDrivers();
        },
        methods: {
            getDrivers () {
                axios.post('/company/drivers/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { drivers, page, per_page, total } = response.data;

                    this.table.data = drivers;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            destroy (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Are you sure you want to delete this driver ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get('/company/drivers/destroy/' + id).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Driver has been deleted.');
                            this.getDrivers();
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