<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Companies" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb>
                                <el-breadcrumb-item>Companies</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="mt-4 bg-white" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getCompanies()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Name -->
                                <el-table-column label="Name">
                                    <template #default="scope">
                                        {{ scope.row.name }}
                                    </template>
                                </el-table-column>

                                <!-- Email -->
                                <el-table-column label="Email">
                                    <template #default="scope">
                                        {{ scope.row.email }}
                                    </template>
                                </el-table-column>

                                <!-- Address -->
                                <el-table-column label="Address">
                                    <template #default="scope">
                                        {{ scope.row.address }}
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
                                            <el-button type="default" size="small" @click="$root.redirect('/admin/companies/' + scope.row.id + '/edit')">
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
                                    @current-change="getCompanies" 
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
                }
            }
        },
        mounted () {
            this.getCompanies();
        },
        methods: {
            getCompanies () {
                axios.post('/admin/companies/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { companies, page, per_page, total } = response.data;

                    this.table.data = companies;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            destroy (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Are you sure you want to delete this company ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(`/admin/companies/${ id }/destroy`).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Company has been deleted.');
                            this.getCompanies();
                        });
                    }
                });
            }
        }
    }
</script>