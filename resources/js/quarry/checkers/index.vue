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
                            <el-breadcrumb>
                                <el-breadcrumb-item>Checkers</el-breadcrumb-item>
                            </el-breadcrumb>
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
                                        <span class="color-oa">
                                            {{ scope.row.firstname }} {{ scope.row.lastname }}
                                        </span>
                                    </template>
                                </el-table-column>

                                <!-- Email -->
                                <el-table-column label="Email">
                                    <template #default="scope">
                                        {{ scope.row.email }}
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
                }
            }
        },
        mounted () {
            this.getCheckers();
        },
        methods: {
            getCheckers () {
                axios.post('/quarry/checkers/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { checkers, page, per_page, total } = response.data;

                    this.table.data = checkers;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            }
        }
    }
</script>