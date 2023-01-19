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
                            <el-breadcrumb>
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/quarry/products">Products</el-link>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item>{{ id === undefined ? 'Add' : 'Edit' }}</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="bg-white mt-4" id="card-wrapper">
                            <el-form label-position="top" class="custom-form normal-label row p-4" require-asterisk-position="right" status-icon>
                                <!-- Product -->
                                <el-form-item label="Product" :error="form.errors.product_id" required>
                                    <el-select v-model="form.inputs.product_id" class="custom w-100" size="large" placeholder="Select Product..." :validate-event="false">
                                        <el-option v-for="product in options.products" :key="product.id" :label="product.name" :value="product.id" />
                                    </el-select>
                                </el-form-item>

                                <!-- Line Break -->
                                <div class="w-100 m-0"></div>

                                <!-- Price -->
                                <el-form-item label="Price" class="col-md-4" :error="form.errors.price" required>
                                    <el-input-number v-model="form.inputs.price" class="custom w-100" size="large" :controls="false" :min="0" :validate-event="false" />
                                </el-form-item>

                                <!-- Extraction Fee -->
                                <el-form-item label="Extraction Fee" class="col-md-4" :error="form.errors.ef" required>
                                    <el-input-number v-model="form.inputs.ef" class="custom w-100" size="large" :controls="false" :min="0" :validate-event="false" />
                                </el-form-item>

                                <!-- Road Maintenance Fee -->
                                <el-form-item label="Road Maintenance Fee" class="col-md-4" :error="form.errors.rmf" required>
                                    <el-input-number v-model="form.inputs.rmf" class="custom w-100" size="large" :controls="false" :min="0" :validate-event="false" />
                                </el-form-item>

                                <!-- Save Button -->
                                <div class="text-end">
                                    <el-button class="mt-4 text-white custom" color="#e66460" native-type="submit" size="large" @click="id === undefined ? save() : update()" :loading="form.loading">
                                        {{ id === undefined ? 'Save' : 'Update' }}
                                    </el-button>
                                </div>
                            </el-form>
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
                        product_id: 'Product',
                        price: 'Price',
                        ef: 'Extraction Fee',
                        rmf: 'Road Maintenance Fee'
                    },
                    errors: {
                        product_id: '',
                        price: '',
                        ef: '',
                        rmf: ''
                    },
                    inputs: {
                        product_id: '',
                        price: 0,
                        ef: 0,
                        rmf: 0
                    },
                    loading: false
                },
                options: {
                    products: []
                }
            }
        },
        mounted () {
            if (this.id !== undefined) {
                this.getProduct();
            }

            this.getProducts(); 
        },
        methods: {
            getProducts () {
                let data = {}

                if (this.id !== undefined) {
                    data['id'] = this.id;
                }

                axios.post('/options/products', data).then(response => this.options.products = response.data.products);
            },
            save () {
                this.form.loading = true;

                axios.post('/quarry/products/store', this.form.inputs).then(() => {
                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'success', title: 'Success', text: 'Product has been added.' }));
                    this.$root.redirect('/quarry/products');
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            getProduct () {
                const { product_id, price, ef, rmf } = JSON.parse(this.product);

                this.form.inputs.product_id = product_id;
                this.form.inputs.price = price;
                this.form.inputs.ef = ef;
                this.form.inputs.rmf = rmf;
            },
            update () {
                this.form.loading = true;
                
                axios.post('/quarry/products/update/' + this.id, this.form.inputs ).then(() => {
                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'info', title: 'Updated', text: 'Product has been updated.' }));
                    this.$root.redirect('/quarry/products');
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            }
        },
        props: ['id', 'product']
    }
</script>