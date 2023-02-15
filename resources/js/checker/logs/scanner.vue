<template>
    <div class="content">
        <div class="d-flex justify-content-center align-items-center h-100 w-100" v-if="Object.keys(details).length === 0">
            <div :class="{ 'scanner-wrapper': camera === 'auto' }">
                <qrcode-stream :camera="camera" :track="paintOutline" @decode="onDecode" />
            </div>
        </div>

        <div class="d-flex flex-column justify-content-center align-items-center p-3 p-md-4" v-if="Object.keys(details).length > 0">
            <div class="grid" style="--bs-gap: .25rem;">
                <!-- Comments -->
                <div class="g-col-12 mb-5" v-if="details.status && details.status !== null">
                    <el-alert title="Comment" type="info" :description="details.checker_comment" :closable="false" class="w-100" />
                </div>

                <!-- Title -->
                <div class="g-col-12 h4 fw-bold text-center color-oa mb-3">Truck Details</div>

                <!-- Comapany -->
                <div class="g-col-6 fw-bold">Company:</div>
                <div class="g-col-6">{{ details.company.name }}</div>

                <!-- Brand -->
                <div class="g-col-6 fw-bold">Brand:</div>
                <div class="g-col-6">{{ details.truck.brand }}</div>

                <!-- Plate No. -->
                <div class="g-col-6 fw-bold">Plate No.:</div>
                <div class="g-col-6">{{ details.truck.plate_no }}</div>

                <!-- Capacity -->
                <div class="g-col-6 fw-bold">Capacity:</div>
                <div class="g-col-6">{{ details.truck.capacity }}</div>

                <!-- Driver -->
                <div class="g-col-6 fw-bold">Driver:</div>
                <div class="g-col-6">{{ details.driver.name }}</div>
            </div>

            <el-form label-position="top" class="grid mt-5" require-asterisk-position="right" status-icon style="--bs-gap: .25rem;">
                <!-- Title -->
                <div class="g-col-12 h4 fw-bold text-center color-oa mb-3">Product Details</div>

                <!-- Product -->
                <div class="g-col-8 fw-bold">Product:</div>
                <div class="g-col-4 text-end">{{ details.product.name }}</div>

                <!-- Price -->
                <div class="g-col-8 fw-bold">Price:</div>
                <div class="g-col-4 text-end">&#8369;{{ details.price.toFixed(2) }}</div>

                <!-- Extraction Fee -->
                <div class="g-col-8 fw-bold">Extraction Fee:</div>
                <div class="g-col-1 text-end fw-bold">&#x2B;</div>
                <div class="g-col-3 text-end">&#8369;{{ details.ef.toFixed(2) }}</div>

                <!-- Road Maintenance Fee -->
                <div class="g-col-8 fw-bold">Road Maintenance Fee:</div>
                <div class="g-col-1 text-end fw-bold">&#x2B;</div>
                <div class="g-col-3 text-end border-2 border-bottom border-dark">&#8369;{{ details.rmf.toFixed(2) }}</div>

                <!-- Total -->
                <div class="g-col-3 g-start-10 text-end">&#8369;{{ compute().toFixed(2) }}</div>

                <!-- Capacity -->
                <div class="g-col-8 fw-bold">Capacity:</div>
                <div class="g-col-1 g-start-9 text-end fw-bold">&#215;</div>
                <div class="g-col-3 g-start-10 text-end border-2 border-bottom border-dark">{{ details.truck.capacity }}</div>

                <!-- Product Cost -->
                <div class="g-col-3 g-start-10 text-end">&#8369;{{ compute('product-cost').toFixed(2) }}</div>

                <!-- System Fee -->
                <div class="g-col-8 fw-bold">System Fee:</div>
                <div class="g-col-1 g-start-9 text-end fw-bold">&#x2B;</div>
                <div class="g-col-3 g-start-10 text-end border-2 border-bottom border-dark">{{ details.truck.truck__category.otf.toFixed(2) }}</div>

                <!-- Total Cost -->
                <div class="g-col-3 g-start-10 text-end mt-2 p-2 border-2 border border-danger">&#8369;{{ compute('total-cost').toFixed(2) }}</div>

                <!-- Comment -->
                <div class="g-col-12 my-4" v-if="details.checker_comment === null">
                    <el-input v-model="form.inputs.comment" type="textarea" placeholder="Comment here..." />
                </div>

                <!-- Save Button -->
                <div class="g-col-12 mt-4 text-end">
                    <el-button class="custom" type="default" native-type="submit" size="large" :loading="form.loading" @click="reject()">
                        Reject
                    </el-button>

                    <el-button class="text-white custom" color="#e66460" native-type="submit" size="large" :loading="form.loading" @click="approve()">
                        Approve
                    </el-button>
                </div>
            </el-form>
        </div>
    </div>
</template>

<script>
    import { reject } from "lodash";
import { QrcodeStream } from "qrcode-reader-vue3";

    export default {
        data () {
            return {
                camera: 'auto',
                details: {},
                form: {
                    inputs: {
                        comment: ''
                    },
                    loading: false
                }
            }
        },
        methods: {
            paintOutline (detectedCodes, ctx) {
                for (const detectedCode of detectedCodes) {
                    const [ firstPoint, ...otherPoints ] = detectedCode.cornerPoints

                    ctx.lineWidth = 10;
                    ctx.strokeStyle = 'red';

                    ctx.beginPath();
                    ctx.moveTo(firstPoint.x, firstPoint.y);

                    for (const { x, y } of otherPoints) {
                        ctx.lineTo(x, y);
                    }

                    ctx.lineTo(firstPoint.x, firstPoint.y);
                    ctx.closePath();
                    ctx.stroke();
                }
            },
            onDecode (decodedString) {
                const qrData = JSON.parse(decodedString);

                axios.post('/checker/scanner/truck-details',qrData).then(response => {
                    this.details = response.data.details;
                    this.camera = 'off';
                }).catch(error => {
                    const { status, errors } = error.response.data;
                    this.camera = 'off';

                    if (status === 'No Result') {
                        this.$swal.fire({
                            icon: 'warning',
                            title: 'Failed',
                            text: 'Truck not found!',
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Close',
                            didClose: () => this.camera = 'auto'
                        });
                    } else if (status === 'Pending') {
                        this.$swal.fire({
                            icon: 'warning',
                            title: 'Failed',
                            text: 'Truck is already pending for checking!',
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Close',
                            didClose: () => this.camera = 'auto'
                        });
                    }
                });;
            },
            compute (param = 'total') {
                if (param === 'total') {
                    return (this.details.price + this.details.ef + this.details.rmf);
                } else if (param === 'product-cost') {
                    return (this.details.price + this.details.ef + this.details.rmf) * this.details.truck.capacity;
                } else if (param === 'total-cost') {
                    return ((this.details.price + this.details.ef + this.details.rmf) * this.details.truck.capacity) + this.details.truck.truck__category.otf;
                }
            },
            approve () {
                this.form.loading = true;

                axios.post(`/checker/logs/update/${ this.details.id }`).then(() => {
                    this.details = {}
                    this.form.loading = false;

                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Log has been recorded.',
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        didClose: () => this.camera = 'auto'
                    });
                }).catch(error => {
                    const { status, errors } = error.response.data;
                    this.form.loading = false;
                    this.camera = 'auto';

                    if (status === 'No Permission') {
                        this.$root.swalMessage('warning', 'Failed', 'Your dont have permission to enter this quarry!');
                    } else if (status === 'Need to Checkin') {
                        this.$root.swalMessage('warning', 'Failed', 'Truck didn\'t check in. Please check in first!');
                    } else if (status === 'Need to Checkout') {
                        this.$root.swalMessage('warning', 'Failed', 'Truck didn\'t check out last time. Please check out first!');
                    }
                });
            },
            reject () {
                this.form.loading = true;
                let data = { rejected: true }

                if (this.form.inputs.comment !== '') {
                    data['comment'] = this.form.inputs.comment
                }

                axios.post(`/checker/logs/update/${ this.details.id }`, data).then(() => {
                    this.details = {}
                    this.form.loading = false;

                    this.$swal.fire({
                        icon: 'info',
                        title: 'Success',
                        text: 'Log has been rejected.',
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        didClose: () => this.camera = 'auto'
                    });
                });
            },
        },
        components: { QrcodeStream }
    }
</script>