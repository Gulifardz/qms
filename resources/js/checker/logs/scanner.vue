<template>
    <div class="content">
        <div class="d-flex justify-content-center align-items-center h-100 w-100" v-if="Object.keys(details).length === 0">
            <div :class="{ 'scanner-wrapper': camera === 'auto' }">
                <qrcode-stream :camera="camera" :track="paintOutline" @decode="onDecode" />
            </div>
        </div>

        <div class="container d-flex flex-column justify-content-center align-items-center" v-if="Object.keys(details).length > 0">
            <!-- Truck Details -->
            <div class="p-4 w-100">
                <h3 class="fw-bold text-start">Truck Details</h3>
                <div class="w-100 text-start">
                    <b>Company:</b> {{ details.company.name }}
                </div>
                <div class="w-100 text-start">
                    <b>Brand:</b> {{ details.truck.brand }}
                </div>
                <div class="w-100 text-start">
                    <b>Plate No.:</b> {{ details.truck.plate_no }}
                </div>
                <div class="w-100 text-start">
                    <b>Driver:</b> {{ details.driver.name }}
                </div>
                <div class="w-100 text-start">
                    <b>Capacity:</b> {{ details.truck.capacity }}
                </div>
            </div>

            <!-- Product Details -->
            <el-form label-position="top" class="custom-form normal-label p-4 w-100" require-asterisk-position="right" status-icon>
                <!-- Header -->
                <div class="mt-5 mb-4 products-grid header">
                    <div class="fw-bold">Product</div>
                    <div class="fw-bold">Quantity</div>
                    <div class="fw-bold">Price</div>
                    <div class="fw-bold">Extraction Fee</div>
                    <div class="fw-bold">Road Maintenance Fee</div>
                    <div class="fw-bold">Total</div>
                </div>

                <!-- Body Computation -->
                <div class="products-grid body" v-for="(product) in details.bought" :key="product.id">
                    <!-- Product -->
                    <div class="text-muted">{{ product.product.name }}</div>
                    
                    <!-- Quantity -->
                    <div class="text-muted">{{ product.quantity }}</div>

                    <!-- Price -->
                    <div class="text-muted">&#8369;{{ product.price.toFixed(2) }}</div>

                    <!-- Extraction Fee -->
                    <div class="text-muted">&#8369;{{ product.ef.toFixed(2) + ' x ' + product.capacity }}</div>

                    <!-- Road Maintenance Fee -->
                    <div class="text-muted">&#8369;{{ product.rmf.toFixed(2) + ' x ' + product.capacity }}</div>

                    <!-- Total -->
                    <div class="text-muted">&#8369;{{ getPerProductTotal(product).toFixed(2) }}</div>
                </div>

                <!-- Footer Computation -->
                <div class="products-grid body mt-3">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="fw-bold">Products Cost:</div>
                    <div class="text-muted">&#8369;{{ getProductCost().toFixed(2) }}</div>
                </div>

                <!-- Footer Computation -->
                <div class="products-grid body">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="fw-bold">System Fee:</div>
                    <div class="text-muted">&#8369;{{ details.truck.truck__category.otf.toFixed(2) }}</div>
                </div>

                <!-- Footer Computation -->
                <div class="products-grid body mt-5">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="fw-bold">Total Cost:</div>
                    <div class="text-muted">&#8369;{{ (getProductCost() + details.truck.truck__category.otf).toFixed(2) }}</div>
                </div>

                <!-- Save Button -->
                <div class="text-end mt-5">
                    <el-button class="mt-4 text-white custom" color="#e66460" native-type="submit" size="large" :loading="form.loading" @click="verify()">
                        Verify
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
                        capacity: '',
                        products: []
                    },
                    labels: {
                        product_id: 'Products',
                    },
                    errors: {
                        product_id: ''
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
                const { company_id, driver_id } = JSON.parse(decodedString);
                this.form.inputs.company_id = company_id;
                this.form.inputs.driver_id = driver_id;

                axios.post('/checker/scanner/details', { company_id: this.form.inputs.company_id, driver_id: this.form.inputs.driver_id }).then(response => {
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
            getPerProductTotal (product) {
                const totalPrice = product.price * product.quantity;
                const totalEF = product.ef * product.capacity;
                const totalRMF = product.rmf * product.capacity;

                return totalPrice + totalEF + totalRMF;
            },
            getProductCost () {
                let cost = 0;

                this.details.bought.forEach(product => {
                    const totalPrice = product.price * product.quantity;
                    const totalEF = product.ef * product.capacity;
                    const totalRMF = product.rmf * product.capacity;

                    cost += (totalPrice + totalEF + totalRMF);
                });
                
                return cost;
            },
            verify () {
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
        },
        components: { QrcodeStream }
    }
</script>