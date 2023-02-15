<template>
    <div class="dashboard">
        <el-container class="dashboard__wrapper">
            <my-sidebar page="Dashboard" />

            <el-container class="dashboard__content">
                <my-header />

                <el-main class="dashboard__main">
                    <div class="container-fluid">
                        <!-- Breadcrumb -->
                        <div class="d-flex justify-content-between align-items-center">
                            <el-breadcrumb>
                                <el-breadcrumb-item>Dashboard</el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>

                        <div class="grid mt-4">
                            <div class="g-col-12 g-col-lg-7">
                                <div class="bg-white p-4" id="card-wrapper">
                                    <Bar :options="chart.monthly_revenue.chartOptions" :data="chart.monthly_revenue.chartData" v-if="chart.monthly_revenue.loaded" />
                                </div>
                            </div>
                            
                            <!-- <div class="g-col-lg-5">
                                <div class="bg-white p-4" id="card-wrapper">
                                    <Pie :options="chart.product_sales.chartOptions" :data="chart.product_sales.chartData" v-if="chart.product_sales.loaded" />
                                </div>
                            </div> -->
                        </div>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    import { Bar, Pie } from 'vue-chartjs';
    import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    export default {
        data () {
            return {
                chart: {
                    monthly_revenue: {
                        loaded: false,
                        chartData: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [{
                                label: new Date().getFullYear() - 1,
                                data: [],
                                backgroundColor: ['#333333'],
                                borderRadius: 50,
                                borderSkipped: false
                            }, {
                                label: new Date().getFullYear(),
                                data: [],
                                backgroundColor: ['#466964'],
                                borderRadius: 50,
                                borderSkipped: false
                            }],
                        },
                        chartOptions: {
                            responsive: true,
                            aspectRatio: 3,
                            categoryPercentage: .6,
                            barPercentage: .8,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Monthly Revenue Chart',
                                },
                                legend: {
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'rectRounded'
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (tooltipItem, dataIndex) => {
                                            return `\u20B1${ Number(tooltipItem.raw).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false,
                                    }
                                },
                                y: {
                                    ticks: {
                                        callback: (value, index, values) => {
                                            return `\u20B1${ value }`;
                                        }
                                    }
                                }
                            },
                        }
                    },
                    product_sales: {
                        loaded: true,
                        chartData: {
                            labels: ['A', 'B', 'C'],
                            datasets: [{
                                label: new Date().getFullYear() - 1,
                                data: [],
                                backgroundColor: ['#333333'],
                                borderRadius: 50,
                                borderSkipped: false
                            }],
                        },
                        chartOptions: {
                            responsive: true,
                            // plugins: {
                            //     title: {
                            //         display: true,
                            //         text: 'Monthly Revenue Chart',
                            //     },
                            //     legend: {
                            //         labels: {
                            //             usePointStyle: true,
                            //             pointStyle: 'rectRounded'
                            //         }
                            //     },
                            //     tooltip: {
                            //         callbacks: {
                            //             label: (tooltipItem, dataIndex) => {
                            //                 return `\u20B1${ Number(tooltipItem.raw).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }`;
                            //             }
                            //         }
                            //     }
                            // }
                        }
                    },
                },
                
            }
        },
        mounted () {
            this.getMonthlyRevenue();
        },
        methods: {
            getMonthlyRevenue () {
                this.chart.monthly_revenue.loaded = false;

                axios.get('/admin/logs/monthly-revenue').then(response => {
                    response.data[0].forEach((month, index) => this.chart.monthly_revenue.chartData.datasets[0].data[index] = month.reduce((accumulator, object) => accumulator + object.total_price, 0));
                    response.data[1].forEach((month, index) => this.chart.monthly_revenue.chartData.datasets[1].data[index] = month.reduce((accumulator, object) => accumulator + object.total_price, 0));

                    this.chart.monthly_revenue.loaded = true;
                });
            } 
        },
        components: { Bar, Pie },
    }
</script>