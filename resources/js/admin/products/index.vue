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
                        </div>

                        <!-- Table -->
                        <div class="table-form-wrapper mt-4">
                            <!-- Table -->
                            <div>
                                <div class="bg-white" id="card-wrapper">
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
                                            {{ scope.row.name }}
                                            </template>
                                        </el-table-column>

                                        <!-- Action -->
                                        <el-table-column label="Action">
                                            <template #default="scope">
                                                <el-button-group>
                                                    <el-button type="default" size="small" @click="edit(scope.row)">
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
                                            @current-change="getProducts" 
                                            background 
                                            small
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form -->
                            <div>
                                <div  class="bg-white p-4" id="card-wrapper">
                                    <el-form label-position="top" class="custom-form normal-label" require-asterisk-position="right" status-icon>
                                        <!-- Form Title -->
                                        <h4 class="text-center mb-4">{{ form.inputs.id === '' ? 'Add' : 'Edit' }} Product</h4>

                                        <!-- Name -->
                                        <el-form-item label="Name" :error="form.errors.name" required>
                                            <el-input v-model="form.inputs.name" class="custom m-0" size="large" placeholder="Product Name" :validate-event="false" />
                                        </el-form-item>

                                        <!-- Login Button -->
                                        <div class="d-flex mt-4">
                                            <el-button class="w-100 custom" type="default" size="large" @click="clear()">Clear</el-button>
                                            <el-button class="w-100 text-white custom" color="#e66460" native-type="submit" size="large" @click="form.inputs.id === '' ? add() : update()" :loading="form.loading">Save</el-button>
                                        </div>
                                    </el-form>
                                </div>
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
            this.getProducts();
        },
        methods: {
            getProducts () {
                axios.post('/admin/products/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { products, page, per_page, total } = response.data;

                    this.table.data = products;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            add () {
                this.form.loading = true;

                axios.post('/admin/products/store', this.form.inputs).then(() => {
                    this.form.loading = false;

                    this.clear();
                    this.$root.swalMessage('success', 'Added', 'Product has been added');
                    this.$root.clearErrors(this.form.errors);
                    this.getProducts();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            edit (product) {
                this.$root.clearErrors(this.form.errors);

                this.form.inputs.id = product.id;
                this.form.inputs.name = product.name;
            },
            update () {
                this.form.loading = true;

                axios.post('/admin/products/update', this.form.inputs).then(() => {
                    this.form.loading = false;

                    this.clear();
                    this.$root.swalMessage('success', 'Updated', 'Product has been updated');
                    this.$root.clearErrors(this.form.errors);
                    this.getProducts();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
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
                        axios.get(`/admin/products/destroy/${ id }`).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Product has been deleted.');
                            this.getProducts();
                        });
                    }
                });
            },
            clear () {
                this.form.inputs.id = '';
                this.form.inputs.name = '';
            }
        }
    }
</script>