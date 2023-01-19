<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Quarries" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Quarries</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="mt-4 bg-white" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getQuarries()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Name -->
                                <el-table-column label="Name">
                                    <template #default="scope">
                                       <el-link class="color-oa" :underline="false" :href="`/admin/quarries/${ scope.row.id }/companies`">{{ scope.row.name }}</el-link>
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
                                            <el-button type="default" size="small" @click="$root.redirect('/admin/quarries/' + scope.row.id + '/edit')">
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
                                    @current-change="getQuarries" 
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
                quarries: [],
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
            this.getQuarries();
        },
        methods: {
            getQuarries () {
                axios.post('/admin/quarries/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { quarries, page, per_page, total } = response.data;

                    this.table.data = quarries;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            destroy (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Are you sure you want to delete this quarry ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(`/admin/quarries/${ id }/destroy`).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Quarry has been deleted.');
                            this.getQuarries();
                        });
                    }
                });
            }
        }
    }
</script>