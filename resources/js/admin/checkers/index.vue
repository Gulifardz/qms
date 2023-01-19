<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Checkers" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Checkers</el-breadcrumb-item>
                            </el-breadcrumb>

                            <el-button class="text-white" color="#e66460" size="small" @click="$root.redirect('/admin/checkers/add')">Add Checker</el-button>
                        </div>

                        <div class="bg-white mt-4" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getCheckers()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Name -->
                                <el-table-column label="Name">
                                    <template #default="scope">
                                        {{ scope.row.firstname }} {{ scope.row.lastname }}
                                    </template>
                                </el-table-column>

                                <!-- Email -->
                                <el-table-column label="Email">
                                    <template #default="scope">
                                        {{ scope.row.email }}
                                    </template>
                                </el-table-column>

                                <!-- Quarry -->
                                <el-table-column label="Quarry">
                                    <template #default="scope">
                                        <el-tag>{{ scope.row.quarry.name }}</el-tag>
                                    </template>
                                </el-table-column>

                                <!-- Action -->
                                <el-table-column label="Action">
                                    <template #default="scope">
                                        <el-button-group>
                                            <el-button type="default" size="small" @click="$root.redirect(`/admin/checkers/edit/${ scope.row.id }`)">
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
                                    @current-change="getCheckers" 
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
                }
            }
        },
        mounted () {
            this.getCheckers();
        },
        methods: {
            getCheckers () {
                axios.post('/admin/checkers/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { checkers, page, per_page, total } = response.data;

                    this.table.data = checkers;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            edit (id) {
                window.location = '/admin/checkers/edit/' + id;
            },
            destroy (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Are you sure you want to delete this checker ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get('/admin/checkers/destroy/' + id).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Checker has been deleted.');
                            this.getCheckers();
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