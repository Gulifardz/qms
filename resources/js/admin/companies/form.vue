<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Companies" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb>
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/admin/companies">Companies</el-link>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item>Edit</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="bg-white mt-4" id="card-wrapper">
                            <el-form label-position="top" class="custom-form normal-label row p-4" require-asterisk-position="right" status-icon>
                                <!-- Name -->
                                <el-form-item label="Name" :error="form.errors.name" required>
                                    <el-input v-model="form.inputs.name" class="custom" size="large" placeholder="Name" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Email -->
                                <el-form-item label="Email" class="col-md-6" :error="form.errors.email" required>
                                    <el-input v-model="form.inputs.email" class="custom" size="large" placeholder="Email" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Contact No. -->
                                <el-form-item label="Contact No." class="col-md-6" :error="form.errors.contact_no" required>
                                    <el-input v-model="form.inputs.contact_no" class="custom" size="large" placeholder="Contact No." clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Address -->
                                <el-form-item label="Address" :error="form.errors.address" required>
                                    <el-input v-model="form.inputs.address" class="custom" type="textarea" placeholder="Address" :validate-event="false" />
                                </el-form-item>

                                <!-- Save Button -->
                                <div class="text-end">
                                    <el-button class="mt-4 text-white custom" color="#e66460" native-type="submit" size="large" @click="update()" :loading="form.loading">
                                        Update
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
                        name: 'Name',
                        email: 'Email',
                        contact_no: 'Contact No.',
                        address: 'Address'
                    },
                    errors: {
                        name: '',
                        email: '',
                        contact_no: '',
                        address: ''
                    },
                    inputs: {
                        id: '',
                        name: '',
                        email: '',
                        contact_no: '',
                        address: ''
                    },
                    loading: false
                }
            }
        },
        mounted () { 
            this.getCompany();
        },
        methods: {
            getCompany () {
                this.form.inputs = JSON.parse(this.company);
            },
            update () {
                this.form.loading = true;

                axios.post(`/admin/companies/${ this.form.inputs.id }/update`, this.form.inputs ).then(() => {
                    this.form.loading = false;

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'info', title: 'Updated', text: 'Company has been updated.' }));
                    this.$root.redirect('/admin/companies');
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
        },
        props: ['company']
    }
</script>