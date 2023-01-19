<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Quarries" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb>
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/admin/quarries">Quarries</el-link>
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

                                <!-- LGU -->
                                <el-form-item label="LGU" :error="form.errors.lgu" required>
                                    <el-input v-model="form.inputs.lgu" class="custom" type="textarea" placeholder="LGU" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Host Barangay -->
                                <el-form-item label="Host Barangay" :error="form.errors.host_barangay">
                                    <el-input v-model="form.inputs.host_barangay" class="custom" type="textarea" placeholder="Host Barangay" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Quarry Area Technical Description -->
                                <el-form-item label="Quarry Area Technical Description (Coordinates/Grid)" :error="form.errors.quarry_area_td">
                                    <el-input v-model="form.inputs.quarry_area_td" class="custom" type="textarea" placeholder="Quarry Area Technical Description (Coordinates/Grid)" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Ingress / Egress Route -->
                                <el-form-item label="Ingress / Egress Route" :error="form.errors.ie_route">
                                    <el-select v-model="form.inputs.ie_route" clearable multiple filterable allow-create
                                        default-first-option :reserve-keyword="false" placeholder="Add Route..." size="large" class="w-100" :validate-event="false" />
                                </el-form-item>
                
                                <!-- Provincial -->
                                <el-form-item label="Provincial" :error="form.errors.provincial_permit">
                                    <el-select v-model="form.inputs.provincial_permit" clearable multiple filterable allow-create
                                        default-first-option :reserve-keyword="false" placeholder="Add Permit..." size="large" class="w-100" :validate-event="false" />
                                </el-form-item>

                                <!-- Regional -->
                                <el-form-item label="Regional MGB" :error="form.errors.regional_permit">
                                    <el-select v-model="form.inputs.regional_permit" clearable multiple filterable allow-create
                                        default-first-option :reserve-keyword="false" placeholder="Add Permit..." size="large" class="w-100" :validate-event="false" />
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
                        address: 'Address',
                        lgu: 'LGU',
                        host_barangay: 'Host Barangay',
                        quarry_area_td: 'Quarry Area Technical Description',
                        ie_route: 'Ingress / Egress Route',
                        provincial_permit: 'Provincial',
                        regional_permit: 'Regional'
                    },
                    errors: {
                        name: '',
                        email: '',
                        contact_no: '',
                        address: '',
                        lgu: '',
                        host_barangay: '',
                        quarry_area_td: '',
                        ie_route: '',
                        provincial_permit: '',
                        regional_permit: ''
                    },
                    inputs: {
                        id: '',
                        name: '',
                        email: '',
                        contact_no: '',
                        address: '',
                        lgu: '',
                        host_barangay: '',
                        quarry_area_td: '',
                        ie_route: [],
                        provincial_permit: [],
                        regional_permit: []
                    },
                    loading: false
                }
            }
        },
        mounted () { 
            this.getQuarry();
        },
        methods: {
            getQuarry () {
                this.form.inputs = JSON.parse(this.quarry);
            },
            update () {
                this.form.loading = true;

                axios.post(`/admin/quarries/${ this.form.inputs.id }/update`, this.form.inputs ).then(() => {
                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'info', title: 'Updated', text: 'Quarry has been updated.' }));
                    this.$root.redirect('/admin/quarries');
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
        },
        props: ['quarry']
    }
</script>