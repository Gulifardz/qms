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
                                <el-breadcrumb-item>QR Code</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <!-- Card -->
                        <div class="d-flex justify-content-center mt-4">
                            <div class="card shadow-sm">
                                <div class="card-body p-4">
                                    <qrcode-vue :value="JSON.stringify({ company_id: $root.user.id, driver_id: driver_info.id, truck_id: driver_info.truck_id })" :size="300" level="H" />
                                    <div class="text-center mt-3">
                                        <el-button type="primary" size="small" @click="downloadQrCode()">Download</el-button>
                                    </div>
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
    import QrcodeVue from "qrcode.vue";

    export default {
        data () {
            return {
                driver_info: {
                    id: '',
                    license_no: ''
                }
            }
        }, 
        mounted () {
            const { id, license_no } = JSON.parse(this.driver);

            this.driver_info.id = id;
            this.driver_info.license_no = license_no;
        },
        methods: {
            downloadQrCode () {
                let canvasImage = this.$el.getElementsByTagName('canvas')[0].toDataURL('image/png');
                let xhr = new XMLHttpRequest();
                const name = this.driver_info.license_no;

                xhr.responseType = 'blob';
                xhr.onload = function () {
                    let a = document.createElement('a');
                    a.href = window.URL.createObjectURL(xhr.response);
                    a.download = name + '.png';
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                };

                xhr.open('GET', canvasImage);
                xhr.send();
            }
        },
        components: { QrcodeVue },
        props: ['driver']
    }
</script>