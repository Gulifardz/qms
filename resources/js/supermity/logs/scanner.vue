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
                <div class="g-col-6 fw-bold">Product:</div>
                <div class="g-col-6">
                    <el-form-item>
                        <el-select v-model="form.inputs.product_id" class="custom w-100 m-0" size="large" placeholder="Select Product..." @change="getProduct()">
                            <el-option v-for="product in options.products" :key="product.id" :label="product.name" :value="product.id" />
                        </el-select>
                    </el-form-item>
                </div>

                <!-- Price -->
                <div class="g-col-8 fw-bold" v-if="form.inputs.product_id !== ''">Price:</div>
                <div class="g-col-4 text-end" v-if="form.inputs.product_id !== ''">&#8369;{{ form.inputs.price.toFixed(2) }}</div>

                <!-- Extraction Fee -->
                <div class="g-col-8 fw-bold" v-if="form.inputs.product_id !== ''">Extraction Fee:</div>
                <div class="g-col-1 text-end fw-bold" v-if="form.inputs.product_id !== ''">&#x2B;</div>
                <div class="g-col-3 text-end" v-if="form.inputs.product_id !== ''">&#8369;{{ form.inputs.ef.toFixed(2) }}</div>

                <!-- Road Maintenance Fee -->
                <div class="g-col-8 fw-bold" v-if="form.inputs.product_id !== ''">Road Maintenance Fee:</div>
                <div class="g-col-1 text-end fw-bold" v-if="form.inputs.product_id !== ''">&#x2B;</div>
                <div class="g-col-3 text-end border-2 border-bottom border-dark" v-if="form.inputs.product_id !== ''">&#8369;{{ form.inputs.rmf.toFixed(2) }}</div>

                <!-- Total -->
                <div class="g-col-3 g-start-10 text-end" v-if="form.inputs.product_id !== ''">&#8369;{{ compute().toFixed(2) }}</div>

                <!-- Capacity -->
                <div class="g-col-8 fw-bold" v-if="form.inputs.product_id !== ''">Capacity:</div>
                <div class="g-col-1 g-start-9 text-end fw-bold" v-if="form.inputs.product_id !== ''">&#215;</div>
                <div class="g-col-3 g-start-10 text-end border-2 border-bottom border-dark" v-if="form.inputs.product_id !== ''">{{ details.truck.capacity }}</div>

                <!-- Product Cost -->
                <div class="g-col-3 g-start-10 text-end" v-if="form.inputs.product_id !== ''">&#8369;{{ compute('product-cost').toFixed(2) }}</div>

                <!-- System Fee -->
                <div class="g-col-8 fw-bold" v-if="form.inputs.product_id !== ''">System Fee:</div>
                <div class="g-col-1 g-start-9 text-end fw-bold" v-if="form.inputs.product_id !== ''">&#x2B;</div>
                <div class="g-col-3 g-start-10 text-end border-2 border-bottom border-dark" v-if="form.inputs.product_id !== ''">{{ details.truck.truck__category.otf.toFixed(2) }}</div>

                <!-- Total Cost -->
                <div class="g-col-3 g-start-10 text-end mt-2 p-2 border-2 border border-danger" v-if="form.inputs.product_id !== ''">&#8369;{{ compute('total-cost').toFixed(2) }}</div>

                <!-- Save Button -->
                <div class="g-col-12 mt-4 text-end" v-if="form.inputs.product_id !== ''">
                    <el-button class="text-white custom" color="#e66460" native-type="submit" size="large" :loading="form.loading" :disabled="compute() === 0" @click="save()">
                        Save
                    </el-button>
                </div>
            </el-form>
        </div>
    </div>
</template>

<script>
    import { QrcodeStream } from "qrcode-reader-vue3";

    export default {
        data () {
            return {
                camera: 'auto',
                details: {},
                form: {
                    inputs: {
                        company_id: '',
                        driver_id: '',
                        truck_id: '',
                        product_id: '',
                        price: 0,
                        ef: 0,
                        rmf: 0
                    },
                    loading: false
                },
                options: {
                    products: []
                },
            }
        },
        methods: {
            getProducts () {
                axios.post('/options/products').then(response => this.options.products = response.data.products);
            },
            getProduct () {
                axios.post('/supermity/scanner/product-details', { product_id: this.form.inputs.product_id }).then(response => {
                    const { price, ef, rmf } = response.data.product;

                    this.form.inputs.price = price;
                    this.form.inputs.ef = ef;
                    this.form.inputs.rmf = rmf;
                });
            },
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
                
                this.form.inputs.company_id = qrData.company_id;
                this.form.inputs.driver_id = qrData.driver_id;

                axios.post('/supermity/scanner/truck-details', qrData).then(response => {
                    this.details = response.data.details;
                    this.form.inputs.truck_id = response.data.details.truck_id;

                    if (response.data.details.status && response.data.details.status !== null) {
                        this.form.inputs.product_id = response.data.details.product_id;
                        this.form.inputs.price = response.data.details.price;
                        this.form.inputs.ef = response.data.details.ef;
                        this.form.inputs.rmf = response.data.details.rmf;
                    }

                    this.getProducts();
                    this.camera = 'off';
                }).catch(error => {
                    const { status, errors } = error.response.data;
                    this.camera = 'off';

                    if (status === 'Pending') {
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
                });
            },
            compute (param = 'total') {
                if (param === 'total') {
                    return (this.form.inputs.price + this.form.inputs.ef + this.form.inputs.rmf);
                } else if (param === 'product-cost') {
                    return (this.form.inputs.price + this.form.inputs.ef + this.form.inputs.rmf) * this.details.truck.capacity;
                } else if (param === 'total-cost') {
                    return ((this.form.inputs.price + this.form.inputs.ef + this.form.inputs.rmf) * this.details.truck.capacity) + this.details.truck.truck__category.otf;
                }
            },
            save () {
                this.form.loading = true;

                if (this.details.status && this.details.status === 422) {
                    axios.post(`/supermity/logs/update/${ this.details.id }`).then(() => {
                        this.details = {}
                        this.form.loading = false;

                        this.$swal.fire({
                            icon: 'info',
                            title: 'Success',
                            text: 'Log has been saved.',
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Close',
                            didClose: () => this.camera = 'auto'
                        });
                    });
                } else {
                    axios.post('/supermity/logs/store', this.form.inputs).then(() => {
                        this.details = {}
                        this.form.loading = false;
                        this.form.inputs = {
                            company_id: '',
                            driver_id: '',
                            truck_id: '',
                            product_id: '',
                            price: 0,
                            ef: 0,
                            rmf: 0
                        };

                        this.$swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Log has been recorded.',
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Close',
                            didClose: () => this.camera = 'auto'
                        });
                    });
                }
            },
        },
        components: { QrcodeStream }
    }
</script>