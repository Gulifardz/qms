<template>
    <div class="wrapper">
        <div class="image-wrapper"></div>

        <div class="form-wrapper">
            <el-form label-position="top" class="custom-form normal-label" require-asterisk-position="right" status-icon>
                <!-- OA Logo -->
                <div class="text-center mb-3">
                    <img src="/img/oa-favicon.ico" alt="">
                </div>

                <!-- Sign In -->
                <h2 class="fw-bold text-center">Welcome back</h2>

                <!-- Description -->
                <div class="text-muted text-center small mb-4">Welcome back ! Please enter your credentials.</div>

                <!-- Email -->
                <el-form-item label="Email" :error="form.errors.email" required>
                    <el-input v-model="form.inputs.email" size="large" placeholder="Enter your Email" :validate-event="false" />
                </el-form-item>

                <!-- Password -->
                <el-form-item label="Password" :error="form.errors.password" required>
                    <el-input v-model="form.inputs.password" size="large" placeholder="Enter your Password" :validate-event="false" show-password />
                </el-form-item>

                <!-- Login Button -->
                <el-button color="#e66460" class="w-100 mt-4 text-white custom" native-type="submit" type="primary" size="large" @click="attemptLogin()" :loading="form.loading">Sign In</el-button>
            </el-form>

            <!-- Register -->
            <div class="register-wrapper">
                Don't have an account ? <el-link href="/registration" type="primary" :underline="false" class="fw-bold">Register</el-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                form: {
                    labels: {
                        email: 'Email',
                        password: 'Password'
                    },
                    errors: {
                        email: '',
                        password: ''
                    },
                    inputs: {
                        email: '',
                        password: ''
                    },
                    loading: false
                }
            }
        },
        methods: {
            attemptLogin () {
                this.form.loading = true;

                axios.post('/', this.form.inputs).then(response => {
                    window.location = response.data.redirect;
                }).catch(error => {
                    const { status, errors } = error.response.data;

                    this.form.loading = false;
                    this.$root.clearErrors(this.form.errors);
                    this.$root.showErrors({ status: status, errors: errors }, this.form.errors, this.form.labels);
                });
            },
            gotoRegistrationPage (page) {
                window.location = '/registration/' + page;
            }
        },
    }
</script>