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
                            <el-breadcrumb>
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/admin/quarries">Quarries</el-link>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item>{{ JSON.parse(quarry).name }}</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="mt-4 bg-white" id="card-wrapper">
                            <!-- Filters -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getQuarryCompanies()" clearable />

                                <!-- Status -->
                                <el-select v-model="filters.status" class="custom ms-2" placeholder="Filter Status..." size="large" @change="getQuarryCompanies()" clearable>
                                    <el-option label="Allowed" value="allowed" />
                                    <el-option label="Restricted" value="restricted" />
                                </el-select>
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Name -->
                                <el-table-column label="Name">
                                    <template #default="scope">
                                       {{ scope.row.name }}
                                    </template>
                                </el-table-column>

                                <!-- Permission -->
                                <el-table-column label="Permission">
                                    <template #default="scope">
                                        <span :class="{ 'text-success': scope.row.tagged, 'text-danger': !scope.row.tagged }">
                                            <el-tag type="success" v-if="scope.row.tagged">Allowed</el-tag>
                                            <el-tag type="danger" v-else>Restricted</el-tag>
                                        </span>
                                    </template>
                                </el-table-column>

                                <!-- Action -->
                                <el-table-column label="Action">
                                    <template #default="scope">
                                        <el-button type="default" size="small" @click="scope.row.tagged ? revoke(scope.row.id) : grant(scope.row.id)">
                                            {{ scope.row.tagged ? 'Revoke' : 'Grant' }}
                                        </el-button>
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
                                    @current-change="getQuarryCompanies" 
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
                    search: '',
                    status: ''
                },
                pagination: { 
                    total: 0, 
                    page: 1, 
                    per_page: 10 
                }
            }
        },
        mounted () {
            this.getQuarryCompanies();
        },
        methods: {
            getQuarryCompanies () {
                axios.post(`/admin/quarries/${ JSON.parse(this.quarry).id }/companies/list`, { ...this.filters, ...this.pagination, ...{ quarry_id: JSON.parse(this.quarry).id } }).then(response => {
                    const { quarry_companies, page, per_page, total } = response.data;

                    this.table.data = quarry_companies;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            grant (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Grant Permission',
                    text: 'Once granted all of this companies trucks will be allowed to enter and exit this quarry. Continue ? ',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Grant',
                    confirmButtonColor: '#67C23A'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/admin/quarries/${ JSON.parse(this.quarry).id }/companies/grant`, { quarry_id: JSON.parse(this.quarry).id, company_id: id }).then(() => {
                            this.getQuarryCompanies();
                        });
                    }
                });
                
            },
            revoke (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Revoke Permission',
                    text: 'Once revoked all of this companies trucks will be restricted to enter and exit this quarry. Continue ? ',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Revoke',
                    confirmButtonColor: '#F56C6C'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/admin/quarries/${ JSON.parse(this.quarry).id }/companies/revoke`, { quarry_id: JSON.parse(this.quarry).id, company_id: id }).then(() => {
                            this.getQuarryCompanies();
                        });
                    }
                });
            }
        },
        props: ['quarry']
    }
</script>