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
                    <div class="h6 fw-bold">Product</div>
                    <div class="h6 fw-bold">Quantity</div>
                    <div class="h6 fw-bold">Price</div>
                    <div class="h6 fw-bold">Extraction Fee</div>
                    <div class="h6 fw-bold">Road Maintenance Fee</div>
                    <div class="h6 fw-bold">Total</div>
                </div>

                <!-- Body Computation -->
                <div class="products-grid body" v-for="(product, index) in form.inputs.products" :key="product.id">
                    <!-- Product -->
                    <el-form-item>
                        <el-select v-model="form.inputs.products[index].id" class="custom w-100 m-0" size="large" placeholder="Select Product..." @change="getSelectedProduct(index)">
                            <el-option v-for="product in options.products" :key="product.id" :label="product.name" :value="product.id" />
                        </el-select>
                    </el-form-item>
                    
                    <!-- Price -->
                    <el-form-item>
                        <el-input-number v-model="form.inputs.products[index].quantity" class="custom w-100 m-0" size="large" :min="1" :max="getMaximumQuantity(index)" :controls="false" />
                    </el-form-item>

                    <!-- Price -->
                    <div class="text-muted">&#8369;{{ form.inputs.products[index].price.toFixed(2) }}</div>

                    <!-- Extraction Fee -->
                    <div class="text-muted">&#8369;{{ form.inputs.products[index].ef.toFixed(2) + ' x ' + details.truck.capacity }}</div>

                    <!-- Road Maintenance Fee -->
                    <div class="text-muted">&#8369;{{ form.inputs.products[index].rmf.toFixed(2) + ' x ' + details.truck.capacity }}</div>

                    <!-- Total -->
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">&#8369;{{ getPerProductTotal(index).toFixed(2) }}</div>

                        <el-button type="default" size="small" @click="removeProduct(index)">
                            <i class="fa fa-trash"></i>
                        </el-button>
                    </div>
                </div>

                <!-- Footer Computation -->
                <div class="products-grid body mt-3">
                    <div>
                        <el-button type="default" size="small" :disabled="isAddProductDisabled()" @click="addProduct()">
                            Add Product
                        </el-button>
                    </div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="fw-bold">Products Cost:</div>
                    <div class="text-muted">&#8369;{{ getProductCost().toFixed(2) }}</div>
                </div>

                <!-- Footer Computation -->
                <div class="products-grid body mt-3">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="fw-bold">System Fee:</div>
                    <div class="text-muted">&#8369;{{ details.truck.truck__category.otf.toFixed(2) }}</div>
                </div>

                <!-- Footer Computation -->
                <div class="products-grid body mt-5">
                    <div class="m-0"></div>
                    <div class="m-0"></div>
                    <div class="m-0"></div>
                    <div class="m-0"></div>
                    <div class="m-0 fw-bold">Total Cost:</div>
                    <div class="m-0 fw-bold">&#8369;{{ (getProductCost() +details.truck.truck__category.otf).toFixed(2) }}</div>
                </div>

                <!-- Save Button -->
                <div class="text-end mt-5">
                    <el-button class="mt-4 text-white custom" color="#e66460" native-type="submit" size="large" :loading="form.loading" :disabled="getProductCost() === 0" @click="save()">
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
                },
                options: {
                    products: []
                },
                selected: {
                    products: []
                }
            }
        },
        methods: {
            getProducts () {
                const selected = this.form.inputs.products.map(product => product.id);

                axios.post('/options/products', { selected: selected }).then(response => this.options.products = response.data.products);
            },
            getSelectedProduct (index) {
                const data = {
                    product_id: this.form.inputs.products[index].id,
                    quantity: this.form.inputs.products[index].quantity,
                }

                axios.post('/supermity/scanner/select', data).then(response => {
                    this.form.inputs.products[index] = response.data.product;
                    this.getProducts();
                });
            },
            addProduct () {
                this.form.inputs.products.push({
                    id: '',
                    quantity: 0,
                    price: 0,
                    ef: 0,
                    rmf: 0
                });
            },
            removeProduct (index) {
                this.form.inputs.products.splice(index, 1);

                this.getProducts();
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
                const { company_id, driver_id } = JSON.parse(decodedString);
                this.form.inputs.company_id = company_id;
                this.form.inputs.driver_id = driver_id;

                axios.post('/supermity/scanner/details', { company_id: this.form.inputs.company_id, driver_id: this.form.inputs.driver_id }).then(response => {
                    this.details = response.data.details;
                    this.form.inputs.truck_id = response.data.details.truck_id;
                    this.form.inputs.capacity = response.data.details.truck.capacity;

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
                });;;
            },
            getMaximumQuantity (index) {
                let maxQuantity = 0;
                let remainingQuantity = 0;

                this.form.inputs.products.forEach(product => {
                    remainingQuantity += product.quantity;
                });

                if (this.form.inputs.products[index].quantity === 0) {
                    maxQuantity = this.form.inputs.capacity - remainingQuantity;
                } else {
                    maxQuantity = this.form.inputs.products[index].quantity + (this.form.inputs.capacity - remainingQuantity);
                }

                return maxQuantity;
            },
            isAddProductDisabled () {
                let result = false;

                let remainingQuantity = 0;

                this.form.inputs.products.forEach(product => {
                    remainingQuantity += product.quantity;
                });

                if (this.form.inputs.capacity <= remainingQuantity) {
                    result = true;
                }

                return result;
            },
            getPerProductTotal (index) {
                const totalPrice = this.form.inputs.products[index].price * this.form.inputs.products[index].quantity;
                const totalEF = this.form.inputs.products[index].ef * this.form.inputs.capacity;
                const totalRMF = this.form.inputs.products[index].rmf * this.form.inputs.capacity;

                return totalPrice + totalEF + totalRMF;
            },
            getProductCost () {
                let cost = 0;

                this.form.inputs.products.forEach(product => {
                    const totalPrice = product.price * product.quantity;
                    const totalEF = product.ef * this.form.inputs.capacity;
                    const totalRMF = product.rmf * this.form.inputs.capacity;

                    cost += (totalPrice + totalEF + totalRMF);
                });
                
                return cost;
            },
            save () {
                this.form.loading = true;

                axios.post('/supermity/logs/store', this.form.inputs).then(() => {
                    this.details = {}
                    this.form.loading = false;
                    this.form.inputs.products = [];

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