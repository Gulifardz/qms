<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Logs" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Logs</el-breadcrumb-item>
                            </el-breadcrumb>

                            <el-button class="text-white" color="#e66460" size="small" @click="viewScanner()">View Scanner</el-button>
                        </div>

                        <div class="bg-white mt-4" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getLogs()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto" v-loading="table.loading">
                                <!-- Details -->
                                <el-table-column type="expand">
                                    <template #default="scope">
                                        <div class="px-5 grid" style="--bs-gap: .25rem;">
                                            <!-- Header -->
                                            <div class="g-col-3 fw-bold">Product</div>
                                            <div class="g-col-2 fw-bold">Price</div>
                                            <div class="g-col-2 fw-bold">Extraction Fee</div>
                                            <div class="g-col-3 fw-bold">Road Maintenance Fee</div>
                                            <div class="g-col-2 fw-bold text-end">Total</div>

                                            <!-- Body -->
                                            <div class="g-col-3 fw-bold">{{ scope.row.product.name }}</div>
                                            <div class="g-col-2">&#8369;{{ scope.row.price.toFixed(2) }}</div>
                                            <div class="g-col-2">&#8369;{{ scope.row.ef.toFixed(2) }}</div>
                                            <div class="g-col-3">&#8369;{{ scope.row.rmf.toFixed(2) }}</div>
                                            <div class="g-col-2 text-end">&#8369;{{ compute({ product: scope.row, keyword: 'total' }).toFixed(2) }}</div>

                                            <!-- Capacity -->
                                            <div class="g-start-10 fw-bold text-end mt-4">Capacity</div>
                                            <div class="g-start-11 text-end fw-bold mt-4">&#215;</div>
                                            <div class="g-start-12 text-end border-2 border-bottom border-dark mt-4">{{ scope.row.truck.capacity }}</div>

                                            <!-- Product Cost -->
                                            <div class="g-col-3 g-start-10 text-end">&#8369;{{ compute({ product: scope.row, keyword: 'product-cost' }).toFixed(2) }}</div>

                                            <!-- System Fee -->
                                            <div class="g-start-10 fw-bold text-end">System Fee</div>
                                            <div class="g-start-11 text-end fw-bold">&#x2B;</div>
                                            <div class="g-start-12 text-end border-2 border-bottom border-dark">{{ scope.row.truck.truck__category.otf.toFixed(2) }}</div>

                                            <!-- Total Cost -->
                                            <div class="g-col-2 g-start-11 text-end mt-2 fw-bold">&#8369;{{ compute({ product: scope.row, keyword: 'total-cost' }).toFixed(2) }}</div>
                                        </div>
                                    </template>
                                </el-table-column>

                                <!-- Brand -->
                                <el-table-column label="Brand">
                                    <template #default="scope">
                                        {{ scope.row.truck.brand }}
                                    </template>
                                </el-table-column>

                                <!-- Year Model -->
                                <el-table-column label="Year Model">
                                    <template #default="scope">
                                        {{ scope.row.truck.year_model }}
                                    </template>
                                </el-table-column>

                                <!-- Plate No. -->
                                <el-table-column label="Plate No.">
                                    <template #default="scope">
                                        {{ scope.row.truck.plate_no }}
                                    </template>
                                </el-table-column>

                                <!-- Company -->
                                <el-table-column label="Company">
                                    <template #default="scope">
                                        {{ scope.row.company.name }}
                                    </template>
                                </el-table-column>

                                <!-- Driver -->
                                <el-table-column label="Driver">
                                    <template #default="scope">
                                        {{ scope.row.driver.name }}
                                    </template>
                                </el-table-column>

                                <!-- Supermity -->
                                <el-table-column label="Supermity">
                                    <template #default="scope">
                                        {{ scope.row.supermity.name }}
                                    </template>
                                </el-table-column>

                                <!-- Status -->
                                <el-table-column label="Status">
                                    <template #default="scope">
                                        <div class="d-flex flex-column" v-if="scope.row.check_out !== null">
                                            <el-tag type="success">Approved</el-tag>
                                        </div>
                                        <div v-else>
                                            <el-tag type="warning">Pending for checking...</el-tag>
                                        </div>
                                    </template>
                                </el-table-column>

                                <!-- Check Out -->
                                <el-table-column label="Check Out">
                                    <template #default="scope">
                                        <div class="d-flex flex-column" v-if="scope.row.check_out !== null">
                                            <el-tag class="mb-2">{{ this.$root.momentDateOnly(scope.row.check_out) }}</el-tag>
                                            <el-tag type="danger">{{ this.$root.momentTimeOnly(scope.row.check_out) }}</el-tag>
                                        </div>
                                        <div v-else>N/A</div>
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
                                    @current-change="getLogs" 
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
                usertype: this.$root.user.type,
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
            this.getLogs();
        },
        methods: {
            getLogs () {
                axios.post('/checker/logs/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { logs, page, per_page, total } = response.data;

                    this.table.data = logs;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;

                    this.table.loading = false;
                });
            },
            viewScanner () {
                window.open('/checker/scanner', '_blank');
            },
            compute (param = { product: {}, keyword: '' }) {
                if (param.keyword === 'total') {
                    return (param.product.price + param.product.ef + param.product.rmf);
                } else if (param.keyword === 'product-cost') {
                    return (param.product.price + param.product.ef + param.product.rmf) * param.product.truck.capacity;
                } else if (param.keyword === 'total-cost') {
                    return ((param.product.price + param.product.ef + param.product.rmf) * param.product.truck.capacity) + param.product.truck.truck__category.otf;
                }
            },
        },
    }
</script>