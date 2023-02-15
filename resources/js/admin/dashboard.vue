<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Dashboard" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb>
                                <el-breadcrumb-item>Dashboard</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Cards -->
                        <div class="grid mt-4">
                            <div class="g-col-12 g-col-md-6 g-col-lg-3">
                                <div class="bg bg-primary p-4 rounded text-white shadow-sm">
                                    <div class="mb-2">Total Revenue</div>
                                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                        <i class="fa fa-money fa-2x"></i>
                                        <span>&#8369;{{ this.total.revenue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="g-col-12 g-col-md-6 g-col-lg-3">
                                <div class="bg bg-dark p-4 rounded text-white shadow-sm">
                                    <div class="mb-2">Total Extraction Fee</div>
                                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                        <i class="fa fa-money fa-2x"></i>
                                        <span>&#8369;{{ this.total.ef.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="g-col-12 g-col-md-6 g-col-lg-3">
                                <div class="bg bg-success p-4 rounded text-white shadow-sm">
                                    <div class="mb-2">Total Road Maintenance Fee</div>
                                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                        <i class="fa fa-money fa-2x"></i>
                                        <span>&#8369;{{ this.total.rmf.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="g-col-12 g-col-md-6 g-col-lg-3">
                                <div class="bg bg-warning p-4 rounded text-white shadow-sm">
                                    <div class="mb-2">Total System Fee</div>
                                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                        <i class="fa fa-money fa-2x"></i>
                                        <span>&#8369;{{ this.total.sf.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="mt-4 bg-white" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getReports()" clearable />

                                <!-- Date Filter -->
                                <el-date-picker v-model="filters.dates" class="custom ms-2" size="large" type="daterange" start-placeholder="Start Date" end-placeholder="End Date" @change="getReports()" />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Quarry -->
                                <el-table-column label="Quarry">
                                    <template #default="scope">
                                        {{ scope.row.name }}
                                    </template>
                                </el-table-column>

                                <!-- Total Amount -->
                                <el-table-column label="Total Amount">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.total_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}
                                    </template>
                                </el-table-column>

                                <!-- Extraction Fee -->
                                <el-table-column label="Extraction Fee">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.extraction_fee.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}
                                    </template>
                                </el-table-column>

                                <!-- Road Maintenance Fee -->
                                <el-table-column label="Road Maintenance Fee">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.road_maintenance_fee.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}
                                    </template>
                                </el-table-column>

                                <!-- System Fee -->
                                <el-table-column label="System Fee">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.system_fee.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }}
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
                                    @current-change="getReports" 
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
                    search: '',
                    dates: []
                },
                pagination: { 
                    total: 0, 
                    page: 1, 
                    per_page: 10 
                },
                total: {
                    revenue: 0,
                    ef: 0,
                    rmf: 0,
                    sf: 0
                }
            }
        },
        mounted () {
            this.getReports();
        },
        methods: {
            getReports () {
                axios.post('/admin/dashboard/reports', { ...this.filters, ...this.pagination }).then(response => {
                    const { quarries, page, per_page, total } = response.data;

                    this.table.data = quarries;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;


                    this.total.revenue = 0;
                    this.total.ef = 0;
                    this.total.rmf = 0;
                    this.total.sf = 0;

                    quarries.forEach(report => {
                        this.total.revenue += report.total_amount;
                        this.total.ef += report.extraction_fee;
                        this.total.rmf += report.road_maintenance_fee;
                        this.total.sf += report.system_fee;
                    });
                });
            }
        }
    }
</script>