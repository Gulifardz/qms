<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Supermities" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb>
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/admin/supermities">Supermities</el-link>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item>{{ id === undefined ? 'Add' : 'Edit' }}</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="bg-white mt-4" id="card-wrapper">
                            <el-form label-position="top" class="custom-form normal-label row p-4" require-asterisk-position="right" status-icon>
                                <!-- Quarry -->
                                <el-form-item label="Quarry" class="col-md-6" :error="form.errors.quarry_id" required>
                                    <el-select v-model="form.inputs.quarry_id" class="custom w-100" size="large" placeholder="Select Quarry..." :validate-event="false">
                                        <el-option v-for="quarry in options.quarries" :key="quarry.id" :label="quarry.name" :value="quarry.id" />
                                    </el-select>
                                </el-form-item>

                                <!-- Line Break -->
                                <div class="w-100 m-0"></div>

                                <!-- Firstname -->
                                <el-form-item label="Firstname" class="col-md-6" :error="form.errors.firstname" required>
                                    <el-input v-model="form.inputs.firstname" class="custom" size="large" placeholder="Firstname" :validate-event="false" />
                                </el-form-item>

                                <!-- Lastname -->
                                <el-form-item label="Lastname" class="col-md-6" :error="form.errors.lastname" required>
                                    <el-input v-model="form.inputs.lastname" class="custom" size="large" placeholder="Lastname" :validate-event="false" />
                                </el-form-item>

                                <!-- Email -->
                                <el-form-item label="Email" class="col-md-6" :error="form.errors.email" required>
                                    <el-input v-model="form.inputs.email" class="custom" size="large" placeholder="Email" :validate-event="false" />
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
                        quarry_id: 'Quarry',
                        firstname: 'Firstname',
                        lastname: 'Lastname',
                        email: 'Email'
                    },
                    errors: {
                        quarry_id: '',
                        firstname: '',
                        lastname: '',
                        email: ''
                    },
                    inputs: {
                        quarry_id: '',
                        firstname: '',
                        lastname: '',
                        email: ''
                    },
                    loading: false
                },
                options: {
                    quarries: []
                }
            }
        },
        mounted () {
            this.getQuarries();  

            if (this.id !== undefined) {
                this.getChecker();
            }
        },
        methods: {
            getQuarries () {
                axios.post('/options/quarries').then(response => this.options.quarries = response.data.quarries);
            },
            save () {
                this.form.loading = true;

                axios.post('/admin/supermities/store', this.form.inputs).then(() => {
                    this.form.loading = false;

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'success', title: 'Success', text: 'Supermity has been added.' }));
                    this.back();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            getChecker () {
                const { quarry_id, firstname, lastname, email } = JSON.parse(this.supermity);

                this.form.inputs.quarry_id = quarry_id;
                this.form.inputs.firstname = firstname;
                this.form.inputs.lastname = lastname;
                this.form.inputs.email = email;
            },
            update () {
                this.form.loading = true;

                axios.post('/admin/supermities/update/' + this.id, this.form.inputs ).then(() => {
                    this.form.loading = false;

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'info', title: 'Updated', text: 'Supermity has been updated.' }));
                    this.back();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            back () {
                window.location = '/admin/supermities';
            }
        },
        props: ['id', 'supermity']
    }
</script>