<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Drivers" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb separator="/">
                                <el-breadcrumb-item>
                                    <el-link :underline="false" href="/company/drivers">Drivers</el-link>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item>{{ id === undefined ? 'Add' : 'Edit' }}</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="bg-white mt-4" id="card-wrapper">
                            <el-form label-position="top" class="custom-form normal-label row p-4" require-asterisk-position="right" status-icon>
                                <!-- Picture -->
                                <el-form-item :error="form.errors.picture" required>
                                    <el-upload :file-list="form.inputs.picture" action="#" list-type="picture-card" :on-remove="removeImage" :on-change="changeImage" :auto-upload="false" :limit="1" accept="image/*">
                                        <el-icon><Plus /></el-icon>
                                    </el-upload>
                                </el-form-item>

                                <!-- Firstname -->
                                <el-form-item label="Firstname" class="col-md-4" :error="form.errors.firstname" required>
                                    <el-input v-model="form.inputs.firstname" class="custom" size="large" placeholder="Firstname" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- Middlename -->
                                <el-form-item label="Middlename" class="col-md-4">
                                    <el-input v-model="form.inputs.middlename" class="custom" size="large" placeholder="Middlename" clearable />
                                </el-form-item>

                                <!-- Lastname -->
                                <el-form-item label="Lastname" class="col-md-4" :error="form.errors.lastname" required>
                                    <el-input v-model="form.inputs.lastname" class="custom" size="large" placeholder="Lastname" clearable :validate-event="false" />
                                </el-form-item>

                                <!-- License No. -->
                                <el-form-item label="License No." class="col-md-6" :error="form.errors.license_no" required>
                                    <el-input v-model="form.inputs.license_no" class="custom" size="large" placeholder="License No." clearable :validate-event="false" />
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
    import { Plus } from '@element-plus/icons-vue';

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
                        firstname: 'Firstname',
                        middlename: 'Middlename',
                        lastname: 'Lastname',
                        license_no: 'License No.',
                        contact_no: 'Contact No.',
                        address: 'Address',
                        picture: 'Picture'
                    },
                    errors: {
                        firstname: '',
                        middlename: '',
                        lastname: '',
                        license_no: '',
                        contact_no: '',
                        address: '',
                        picture: ''
                    },
                    inputs: {
                        firstname: '',
                        middlename: '',
                        lastname: '',
                        license_no: '',
                        contact_no: '',
                        address: '',
                        picture: []
                    },
                    loading: false
                },
                now: Math.floor(Date.now() / 1000)
            }
        },
        mounted () {
            if (this.id !== undefined) {
                this.getDriver();
            }
        },
        methods: {
            changeImage (file, fileList) {
                this.form.inputs.picture[0] = fileList[0].raw;
            },
            removeImage (uploadFile, uploadFiles) {
                this.form.inputs.picture = [];
            },
            save () {
                this.form.loading = true;

                const fd =  new FormData();

                fd.append('firstname', this.form.inputs.firstname);
                fd.append('middlename', this.form.inputs.middlename);
                fd.append('lastname', this.form.inputs.lastname);
                fd.append('license_no', this.form.inputs.license_no);
                fd.append('contact_no', this.form.inputs.contact_no);
                fd.append('address', this.form.inputs.address);

                if (this.form.inputs.picture.length > 0) {
                    fd.append('picture', this.form.inputs.picture[0]);
                }

                axios.post('/company/drivers/store', fd).then(() => {
                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'success', title: 'Success', text: 'New driver has been added.' }));
                    this.back();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            getDriver () {
                const { firstname, middlename, lastname, license_no, contact_no, address, picture } = JSON.parse(this.driver);

                this.form.inputs.firstname = firstname;
                this.form.inputs.middlename = middlename;
                this.form.inputs.lastname = lastname;
                this.form.inputs.license_no = license_no;
                this.form.inputs.contact_no = contact_no;
                this.form.inputs.address = address;
                this.form.inputs.picture[0] = { uid: Math.random(), name: picture.split('/').pop(), url: picture + '?' + this.now };
            },
            update () {
                this.form.loading = true;

                const fd =  new FormData();

                fd.append('id', this.id);
                fd.append('firstname', this.form.inputs.firstname);
                fd.append('middlename', this.form.inputs.middlename);
                fd.append('lastname', this.form.inputs.lastname);
                fd.append('license_no', this.form.inputs.license_no);
                fd.append('contact_no', this.form.inputs.contact_no);
                fd.append('address', this.form.inputs.address);

                if (this.form.inputs.picture.length > 0) {
                    fd.append('picture', this.form.inputs.picture[0]);
                } else {
                    fd.append('picture', []);
                }

                axios.post('/company/drivers/update/' + this.id, fd).then(() => {
                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);

                    localStorage.setItem('swal_message', JSON.stringify({ icon: 'success', title: 'Success', text: 'Driver has been updated.' }));
                    this.back();
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            back () {
                window.location = '/company/drivers';
            }
        },
        components: { Plus },
        props: ['id', 'driver']
    }
</script>