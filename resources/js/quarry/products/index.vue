<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Products" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Products</el-breadcrumb-item>
                            </el-breadcrumb>

                            <el-button class="text-white" color="#e66460" size="small" @click="add()">Add Product</el-button>
                        </div>

                        <!-- Card -->
                        <div class="mt-4 bg-white" id="card-wrapper">
                            <!-- Filter -->
                            <div class="d-flex justify-content-end align-items-center p-4">
                                <!-- Search -->
                                <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getProducts()" clearable />
                            </div>

                            <!-- Table -->
                            <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                <!-- Name -->
                                <el-table-column label="Name">
                                    <template #default="scope">
                                       {{ scope.row.product.name }}
                                    </template>
                                </el-table-column>

                                <!-- Price -->
                                <el-table-column label="Price">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.price.toFixed(2) }}
                                    </template>
                                </el-table-column>

                                <!-- Extraction Fee -->
                                <el-table-column label="Extraction Fee">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.ef.toFixed(2) }}
                                    </template>
                                </el-table-column>

                                <!-- Road Maintenance Fee -->
                                <el-table-column label="Road Maintenance Fee">
                                    <template #default="scope">
                                        &#8369;{{ scope.row.rmf.toFixed(2) }}
                                    </template>
                                </el-table-column>

                                <!-- Action -->
                                <el-table-column label="Action">
                                    <template #default="scope">
                                        <el-button-group>
                                            <el-button type="default" size="small" @click="$root.redirect(`/quarry/products/edit/${ scope.row.id }`)">
                                                <i class="fa fa-edit"></i>
                                            </el-button>
                                            <el-button type="default" size="small" @click="destroy(scope.row.id)">
                                                <i class="fa fa-trash"></i></el-button>
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
                                    @current-change="getProducts" 
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
            this.getProducts();
        },
        methods: {
            getProducts () {
                axios.post('/quarry/products/list', { ...this.filters.search, ...this.pagination }).then(response => {
                    const { products, page, per_page, total } = response.data;

                    this.table.data = products;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            add () {
                window.location = '/quarry/products/add';
            },
            destroy (id) {
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Are you sure you want to delete this product ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get('/quarry/products/destroy/' + id).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Product has been deleted.');
                            this.getProducts();
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