<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Truck Categories" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>Truck Categories</el-breadcrumb-item>
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
                                        <el-input v-model="filters.search" class="custom" placeholder="Search..." size="large" @change="getTruckCategories()" clearable />
                                    </div>

                                    <!-- Table -->
                                    <el-table :data="table.data" size="large" class="w-100 custom" table-layout="auto">
                                        <!-- Name -->
                                        <el-table-column label="Name">
                                            <template #default="scope">
                                            {{ scope.row.name }}
                                            </template>
                                        </el-table-column>

                                        <!-- One Time Fee -->
                                        <el-table-column label="One Time Fee">
                                            <template #default="scope">
                                                &#8369;{{ scope.row.otf.toFixed(2) }}
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
                                            @current-change="getTruckCategories" 
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
                                        <h4 class="text-center mb-4">{{ form.inputs.id === '' ? 'Add' : 'Edit' }} Category</h4>

                                        <!-- Name -->
                                        <el-form-item label="Name" :error="form.errors.name" required>
                                            <el-input v-model="form.inputs.name" class="custom m-0" size="large" placeholder="Category Name" :validate-event="false" />
                                        </el-form-item>

                                        <!-- One Time Fee -->
                                        <el-form-item label="One Time Fee" :error="form.errors.otf" required>
                                            <el-input-number v-model="form.inputs.otf" class="custom m-0 w-100" size="large" :step="1" :controls="false" :validate-event="false" />
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
                        name: 'Name',
                        otf: 'One Time Fee'
                    },
                    errors: {
                        name: '',
                        otf: ''
                    },
                    inputs: {
                        id: '',
                        name: '',
                        otf: 0
                    },
                    loading: false
                }
            }
        },
        mounted () {
            this.getTruckCategories();
        },
        methods: {
            getTruckCategories () {
                axios.post('/admin/truck-categories/list', { ...this.filters, ...this.pagination }).then(response => {
                    const { truck_categories, page, per_page, total } = response.data;

                    this.table.data = truck_categories;
                    this.pagination.page = page;
                    this.pagination.per_page = per_page;
                    this.pagination.total = total;
                });
            },
            add () {
                this.form.loading = true;

                axios.post('/admin/truck-categories/store', this.form.inputs).then(() => {
                    this.form.loading = false;

                    this.clear();
                    this.$root.swalMessage('success', 'Added', 'Truck Category has been added');
                    this.$root.clearErrors(this.form.errors);
                    this.getTruckCategories();
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
                this.form.inputs.otf = product.otf;
            },
            update () {
                this.form.loading = true;

                axios.post('/admin/truck-categories/update', this.form.inputs).then(() => {
                    this.form.loading = false;

                    this.clear();
                    this.$root.swalMessage('success', 'Updated', 'Truck Category has been updated');
                    this.$root.clearErrors(this.form.errors);
                    this.getTruckCategories();
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
                    text: 'Are you sure you want to delete this truck category ?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56c6c',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get('/admin/truck-categories/destroy/' + id).then(() => {
                            this.$root.swalMessage('success', 'Success', 'Truck Category has been deleted.');
                            this.getTruckCategories();
                        }).catch(error => {
                            const { status, errors } = error.response.data;

                            this.$root.showErrors({ status: status, errors: errors }, {}, {});
                        });
                    }
                });
            },
            clear () {
                this.form.inputs.id = '';
                this.form.inputs.name = '';
                this.form.inputs.otf = 0;
            }
        }
    }
</script>