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
                            <el-breadcrumb>
                                <el-breadcrumb-item>Logs</el-breadcrumb-item>
                            </el-breadcrumb>
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
                                        <!-- Header -->
                                        <div class="products-grid header px-4 pb-2">
                                            <div class="fw-bold">Product</div>
                                            <div class="fw-bold">Quantity</div>
                                            <div class="fw-bold">Price</div>
                                            <div class="fw-bold">Extraction Fee</div>
                                            <div class="fw-bold">Road Maintenance Fee</div>
                                            <div class="fw-bold">Total</div>
                                        </div>

                                        <!-- Body Computation -->
                                        <div class="products-grid body px-4" v-for="(product) in scope.row.bought" :key="product.id">
                                            <!-- Product -->
                                            <div class="text-muted m-0">{{ product.product.name }}</div>
                                            
                                            <!-- Quantity -->
                                            <div class="text-muted m-0">x{{ product.quantity }}</div>

                                            <!-- Price -->
                                            <div class="text-muted m-0">&#8369;{{ product.price.toFixed(2) }}</div>

                                            <!-- Extraction Fee -->
                                            <div class="text-muted m-0">&#8369;{{ product.ef.toFixed(2) + ' x ' + product.capacity }}</div>

                                            <!-- Road Maintenance Fee -->
                                            <div class="text-muted m-0">&#8369;{{ product.rmf.toFixed(2) + ' x ' + product.capacity }}</div>

                                            <!-- Total -->
                                            <div class="text-muted m-0">&#8369;{{ getPerProductTotal(product).toFixed(2) }}</div>
                                        </div>

                                        <!-- Footer Computation -->
                                        <div class="products-grid body px-4 mt-2">
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0 fw-bold">Products Cost:</div>
                                            <div class="m-0">&#8369;{{ getProductCost(scope.row.bought).toFixed(2) }}</div>
                                        </div>

                                        <!-- Footer Computation -->
                                        <div class="products-grid body px-4">
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0 fw-bold">System Fee:</div>
                                            <div class="m-0">&#8369;{{ scope.row.truck.truck__category.otf.toFixed(2) }}</div>
                                        </div>

                                        <!-- Footer Computation -->
                                        <div class="products-grid body px-4 mt-3">
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0"></div>
                                            <div class="m-0 fw-bold">Total Cost:</div>
                                            <div class="m-0">&#8369;{{ (getProductCost(scope.row.bought) +  scope.row.truck.truck__category.otf).toFixed(2) }}</div>
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

                                <!-- Quarry -->
                                <el-table-column label="Quarry">
                                    <template #default="scope">
                                        {{ scope.row.quarry.name }}
                                    </template>
                                </el-table-column>

                                <!-- Driver -->
                                <el-table-column label="Driver">
                                    <template #default="scope">
                                        {{ scope.row.driver.name }}
                                    </template>
                                </el-table-column>

                                <!-- Status -->
                                <el-table-column label="Status">
                                    <template #default="scope">
                                        <div class="d-flex flex-column" v-if="scope.row.check_out !== null">
                                            <el-tag type="success">Checked</el-tag>
                                        </div>
                                        <div v-else>
                                            <el-tag type="warning">Pending for checking...</el-tag>
                                        </div>
                                    </template>
                                </el-table-column>

                                <!-- Supermity -->
                                <el-table-column label="Supermity">
                                    <template #default="scope">
                                        {{ scope.row.supermity.name }}
                                    </template>
                                </el-table-column>

                                <!-- Check By -->
                                <el-table-column label="Check By">
                                    <template #default="scope">
                                        {{ scope.row.checker.name }}
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
                axios.post('/company/logs/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { logs, page, per_page, total } = response.data;

                    this.table.data = logs;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;

                    this.table.loading = false;
                });
            },
            getPerProductTotal (product) {
                const totalPrice = product.price * product.quantity;
                const totalEF = product.ef * product.capacity;
                const totalRMF = product.rmf * product.capacity;

                return totalPrice + totalEF + totalRMF;
            },
            getProductCost (products) {
                let cost = 0;

                products.forEach(product => {
                    const totalPrice = product.price * product.quantity;
                    const totalEF = product.ef * product.capacity;
                    const totalRMF = product.rmf * product.capacity;

                    cost += (totalPrice + totalEF + totalRMF);
                });
                
                return cost;
            },
        },
    }
</script>