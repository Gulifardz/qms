<template>
    <div class="wrapper">
        <div class="form-wrapper">
            <el-form label-position="top" class="custom-form normal-label" require-asterisk-position="right" status-icon>
                <!-- OA Logo -->
                <div class="text-center mb-3">
                    <img src="/img/oa-favicon.ico" alt="">
                </div>

                <!-- Sign In -->
                <h2 class="fw-bold text-center">{{ form.inputs.type === 'company' ? 'Company' : 'Quarry' }} Registration</h2>

                <!-- Description -->
                <div class="text-muted text-center small mb-4">Please enter {{ form.inputs.type }}'s details.</div>

                <!-- Steps (visible only if registration type if quarry) -->
                <el-steps :active="form.inputs.type === 'company' ? 1 : steps.active" finish-status="success" :align-center="true" class="custom-steps mb-3" v-if="form.inputs.type === 'quarry'">
                    <el-step title="Company Details" />
                    <el-step title="Location" />
                    <el-step title="Permits" />
                </el-steps>

                <!-- Type -->
                <el-form-item label="Registration Type" v-if="steps.active === 1">
                    <el-select v-model="form.inputs.type" class="custom w-100" size="large" @change="$root.clearErrors(form.errors)">
                        <el-option label="Company" value="company" />
                        <el-option label="Quarry" value="quarry" />
                    </el-select>
                </el-form-item>

                <!-- Name -->
                <el-form-item label="Name" :error="form.errors.name" required v-if="steps.active === 1">
                    <el-input v-model="form.inputs.name" class="custom" size="large" placeholder="Name" clearable :validate-event="false" />
                </el-form-item>

                <!-- Email -->
                <el-form-item label="Email" :error="form.errors.email" required v-if="steps.active === 1">
                    <el-input v-model="form.inputs.email" class="custom" size="large" placeholder="Email" clearable :validate-event="false" />
                </el-form-item>

                <!-- Contact No. -->
                <el-form-item label="Contact No." :error="form.errors.contact_no" required v-if="steps.active === 1">
                    <el-input v-model="form.inputs.contact_no" class="custom" size="large" placeholder="Contact No." clearable :validate-event="false" />
                </el-form-item>

                <!-- Address -->
                <el-form-item label="Address" :error="form.errors.address" required v-if="steps.active === 1">
                    <el-input v-model="form.inputs.address" class="custom" type="textarea" placeholder="Address" :validate-event="false" />
                </el-form-item>

                <!-- LGU -->
                <el-form-item label="LGU" :error="form.errors.lgu" required  v-if="form.inputs.type === 'quarry' && steps.active === 2">
                    <el-input v-model="form.inputs.lgu" class="custom" type="textarea" placeholder="LGU" clearable :validate-event="false" />
                </el-form-item>

                <!-- Host Barangay -->
                <el-form-item label="Host Barangay" :error="form.errors.host_barangay" required v-if="form.inputs.type === 'quarry' && steps.active === 2">
                    <el-input v-model="form.inputs.host_barangay" class="custom" type="textarea" placeholder="Host Barangay" clearable :validate-event="false" />
                </el-form-item>

                <!-- Quarry Area Technical Description -->
                <el-form-item label="Quarry Area Technical Description (Coordinates/Grid)" :error="form.errors.quarry_area_td" required v-if="form.inputs.type === 'quarry' && steps.active === 2">
                    <el-input v-model="form.inputs.quarry_area_td" class="custom" type="textarea" placeholder="Quarry Area Technical Description (Coordinates/Grid)" clearable :validate-event="false" />
                </el-form-item>

                <!-- Ingress / Egress Route -->
                <el-form-item label="Ingress / Egress Route" :error="form.errors.ie_route" required v-if="form.inputs.type === 'quarry' && steps.active === 2">
                    <el-select v-model="form.inputs.ie_route" clearable multiple filterable allow-create
                        default-first-option :reserve-keyword="false" placeholder="Add Route..." size="large" class="w-100" :validate-event="false" />
                </el-form-item>
 
                <!-- Provincial -->
                <el-form-item label="Provincial" :error="form.errors.provincial_permit" required v-if="form.inputs.type === 'quarry' && steps.active === 3">
                    <el-select v-model="form.inputs.provincial_permit" clearable multiple filterable allow-create
                        default-first-option :reserve-keyword="false" placeholder="Add Permit..." size="large" class="w-100" :validate-event="false" />
                </el-form-item>

                <!-- Regional -->
                <el-form-item label="Regional MGB" :error="form.errors.regional_permit" required v-if="form.inputs.type === 'quarry' && steps.active === 3">
                    <el-select v-model="form.inputs.regional_permit" clearable multiple filterable allow-create
                        default-first-option :reserve-keyword="false" placeholder="Add Permit..." size="large" class="w-100" :validate-event="false" />
                </el-form-item>

                <!-- Buttons -->
                <div class="d-flex mt-4">
                    <el-button class="w-100" type="default" size="large" @click="back()" v-if="form.inputs.type === 'quarry' && steps.active > 1">Back</el-button>
                    <el-button color="#e66460" class="w-100 text-white custom" native-type="submit" type="primary" size="large" @click="form.inputs.type === 'company' ? signup() : (steps.active < 3 ? next() : signup())" :loading="form.loading">
                        {{ form.inputs.type === 'company' ? 'Register' : (steps.active < 3 ? 'Next' : 'Register') }}
                    </el-button>
                </div>
            </el-form>

            <!-- Login -->
            <div class="login-wrapper">
                Already have an account ? <el-link href="/" type="primary" :underline="false" class="fw-bold">Sign In</el-link>
            </div>
        </div>

        <div class="image-wrapper"></div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                registration_type: 'company',
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
                        type: 'company',
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
                },
                steps: {
                    active: 1
                },
                options: {
                    products: []
                }
            }
        },
        methods: {
            signup () {
                this.form.loading = true;
                let data = this.form.inputs;

                if (this.form.inputs.type === 'company') {
                    data = (({ type, name, email, contact_no, address }) => ({ type, name, email, contact_no, address }))(this.form.inputs);
                }

                axios.post('/registration/' + this.form.inputs.type + '/store', data).then(() => {
                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);

                    localStorage.setItem('el_message', JSON.stringify({ message: 'Registered successfully.', type: 'success' }));
                    window.location = '/';
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            next () {
                this.form.loading = true;
                let data = {}
                
                if (this.steps.active === 1 ) {
                    data = (({ type, name, email, contact_no, address }) => ({ type, name, email, contact_no, address }))(this.form.inputs);
                } else if (this.steps.active === 2) {
                    data = (({ lgu, host_barangay, quarry_area_td, ie_route }) => ({ lgu, host_barangay, quarry_area_td, ie_route }))(this.form.inputs);
                } else if (this.steps.active === 3) {
                    data = (({ provincial_permit, regional_permit }) => ({ provincial_permit, regional_permit }))(this.form.inputs);
                } 

                axios.post('/registration/validate-step', data).then(() => {
                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.steps.active += 1;
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            back () {
                this.steps.active -= 1;
            }
        }
    }
</script>