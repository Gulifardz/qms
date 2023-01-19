require('./bootstrap');

import axios from 'axios';
import { createApp } from 'vue';

import ElementPlus from 'element-plus';
import { ElMessage } from 'element-plus';
import 'element-plus/dist/index.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import SwalDefaultOption from '../json/swal-default.json';

import moment from 'moment/moment';


const app = createApp({
    data () {
        return {
            el_message: localStorage.getItem('el_message'),
            swal_message: localStorage.getItem('swal_message'),
            user: window.user
        }
    },
    methods: {
        elMessage (message, type) {
            ElMessage({ message: message, type: type, onClose: localStorage.removeItem('el_message') });
        },
        swalMessage (icon, title, text) {
            this.$swal.fire({
                icon: icon,
                title: title,
                text: text,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Close',
                didOpen: () => localStorage.removeItem('swal_message')
            });
        },
        showErrors (data, errors, labels) {
            if (data.status === 'Missing Fields') {
                Object.entries(data.errors).forEach(error => {
                    const [key, value] = error;

                    errors[key] = labels[key] + ' ' + value[0];
                });
            } else if (data.status === 'Failed Query') {
                ElMessage.error('Something went wrong. Try Again!');
            } else if (data.status === 'Invalid Credentials') {
                ElMessage.error('Invalid Credential. Try Again!');
            }
        },
        clearErrors (errors) {
            Object.keys(errors).forEach(key => errors[key] = '');
        },
        clearFields (fields) {
            Object.entries(fields).forEach(field => {
                const [key, value] = field;

                if (typeof value === 'string') {
                    fields[key] = '';
                } else if (typeof value === 'object') {
                    fields[key] = [];
                }
            });
        },
        loginPageRedirect () {
            window.location = '/';
        },
        logout () {
            this.$swal.fire({
                icon: 'warning',
                title: 'Logout ?',
                text: 'Are you sure you want to logout ?',
                showCancelButton: true,
                cancelButtonText: 'Close',
                confirmButtonText: 'Logout',
                confirmButtonColor: '#f56c6c'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get('/logout').then(response => window.location = response.data.redirect)
                }
            });
        },
        momentDateOnly (date) {
            return moment(date).format('MMMM D, YYYY');
        },
        momentTimeOnly (date) {
            return moment(date).format('h:mm A');
        },
        redirect (url) {
            window.location = url;
        }
    },
    mounted () {
        if (this.el_message) {
            const { message, type } = JSON.parse(this.el_message);

            this.elMessage(message, type);     
        }

        if (this.swal_message) {
            const { icon, title, text } = JSON.parse(this.swal_message);

            this.swalMessage(icon, title, text);
        }
    }
});


import MySidebar from './layouts/el-sidebar';
import MyHeader from './layouts/el-header';

import * as auth from './routes/auth';
import * as admin from './routes/admin';
import * as company from './routes/company';
import * as quarry from './routes/quarry';
import * as supermity from './routes/supermity';
import * as checker from './routes/checker';

app.component('my-sidebar', MySidebar);
app.component('my-header', MyHeader);

app.component('login-page', auth.Login);
app.component('registration', auth.Registration);

app.component('admin-quarries', admin.QuarriesIndex);
app.component('admin-quarry-form', admin.QuarryForm);
app.component('admin-quarry-companies', admin.QuarryCompanies);

app.component('admin-companies', admin.CompaniesIndex);
app.component('admin-company-form', admin.CompanyForm);

app.component('admin-truck-categories', admin.TruckCategoriesIndex);

app.component('admin-products', admin.ProductsIndex);

app.component('admin-checkers', admin.CheckersIndex);
app.component('admin-checker-form', admin.CheckerForm);

app.component('admin-supermities', admin.SupermitiesIndex);
app.component('admin-supermity-form', admin.SupermityForm);



app.component('company-drivers', company.DriversIndex);
app.component('company-driver-form', company.DriverForm);
app.component('company-driver-qr', company.DriverQr);

app.component('company-trucks', company.TrucksIndex);
app.component('company-truck-form', company.TruckForm);

app.component('company-logs', company.LogsIndex);



app.component('quarry-products', quarry.ProductsIndex);
app.component('quarry-product-form', quarry.ProductForm);

app.component('quarry-checkers', quarry.CheckersIndex);

app.component('quarry-supermities', quarry.SupermitiesIndex);

app.component('quarry-logs', quarry.LogsIndex);




app.component('supermity-logs', supermity.LogsIndex);
app.component('supermity-scanner', supermity.Scanner);




app.component('checker-logs', checker.LogsIndex);
app.component('checker-scanner', checker.Scanner);



app.use(VueSweetalert2, SwalDefaultOption);
app.use(ElementPlus);
app.use(moment);
app.mount('#app');