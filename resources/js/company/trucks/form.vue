<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Trucks" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/company/trucks">Trucks</el-link>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item>{{ id === undefined ? 'Add' : 'Edit' }}</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="bg-white mt-4" id="card-wrapper">
                            <el-form label-position="top" class="custom-form normal-label row p-4" require-asterisk-position="right" status-icon>
                                <!-- Brand -->
                                <el-form-item label="Brand" class="col-md-6" :error="form.errors.brand" required>
                                    <el-input v-model="form.inputs.brand" class="custom" size="large" placeholder="Brand" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Year Model -->
                                <el-form-item label="Year Model" class="col-md-6" :error="form.errors.year_model" required>
                                    <el-input v-model="form.inputs.year_model" class="custom" size="large" placeholder="Year Model" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Capacity -->
                                <el-form-item label="Capacity" class="col-md-6" :error="form.errors.capacity" required>
                                    <el-input-number v-model="form.inputs.capacity" class="custom w-100" size="large" :min="0" :controls="false" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Category -->
                                <el-form-item label="Category" class="col-md-6" :error="form.errors.truck_category_id" required>
                                    <el-select v-model="form.inputs.truck_category_id" placeholder="Select Category..." size="large" class="w-100" :validate-event="false" clearable>
                                        <el-option v-for="(category, index) in options.truck_categories" :key="index" :label="category.name" :value="category.id" />
                                    </el-select>
                                </el-form-item>

                                <!-- OR CR -->
                                <el-form-item label="OR CR" class="col-md-6" :error="form.errors.orcr" required>
                                    <el-input v-model="form.inputs.orcr" class="custom" size="large" placeholder="OR CR" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Plate No. -->
                                <el-form-item label="Plate No." class="col-md-6" :error="form.errors.plate_no" required>
                                    <el-input v-model="form.inputs.plate_no" class="custom" size="large" placeholder="Plate No." clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Drivers -->
                                <el-form-item label="Drivers" :error="form.errors.drivers" required>
                                    <el-select v-model="form.inputs.drivers" placeholder="Select Drivers..." size="large" class="w-100" :validate-event="false" clearable multiple>
                                        <el-option v-for="(driver, index) in options.drivers" :key="index" :label="driver.name" :value="driver.id" />
                                    </el-select>
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
                        brand: 'Brand',
                        year_model: 'Year Model',
                        capacity: 'Capacity',
                        truck_category_id: 'Category',
                        orcr: 'OR CR',
                        plate_no: 'Plate No.',
                        drivers: 'Drivers'
                    },
                    errors: {
                        brand: '',
                        year_model: '',
                        capacity: '',
                        truck_category_id: '',
                        orcr: '',
                        plate_no: '',
                        drivers: '',
                    },
                    inputs: {
                        id: this.id,
                        brand: '',
                        year_model: '',
                        capacity: 0,
                        truck_category_id: '',
                        orcr: '',
                        plate_no: '',
                        drivers: []
                    },
                    loading: false
                },
                options: {
                    drivers: [],
                    truck_categories: []
                }
            }
        },
        mounted () {
            if (this.id !== undefined) {
                this.getTruck();
            }

            this.getDrivers();
            this.getTruckCategories();
        },
        methods: {
            getDrivers () {
                let data = {}

                if (this.id !== undefined) {
                    data['drivers'] = this.form.inputs.drivers
                }

                axios.post('/options/drivers', data).then(response => this.options.drivers = response.data.drivers);
            }, 
            getTruckCategories () {
                axios.post('/options/truck-categories').then(response => this.options.truck_categories = response.data.truck_categories);
            }, 
            save () {
                this.form.loading = true;

                axios.post('/trucks/store', this.form.inputs).then(() => {
                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'success', title: 'Success', text: 'New truck has been added.' }));
                    this.back();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            getTruck () {
                const { brand, year_model, capacity, truck_category_id, orcr, plate_no, drivers } = JSON.parse(this.truck);

                this.form.inputs.brand = brand;
                this.form.inputs.year_model = year_model;
                this.form.inputs.capacity = capacity;
                this.form.inputs.truck_category_id = truck_category_id;
                this.form.inputs.orcr = orcr;
                this.form.inputs.plate_no = plate_no;
                this.form.inputs.drivers = drivers;
            },
            update () {
                this.form.loading = true;

                axios.post('/company/trucks/update/' + this.id, this.form.inputs).then(() => {
                    this.$root.clearErrors(this.form.errors);

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'success', title: 'Success', text: 'Truck has been updated.' }));
                    this.$root.redirect('/company/trucks');
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            }
        },
        props: ['id', 'truck']
    }
</script>