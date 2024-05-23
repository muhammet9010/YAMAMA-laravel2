@extends('layouts/layoutMaster')

@section('title', 'ACWAD')


@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/charts-chartjs.js') }}"></script> --}}
    <script>
        'use strict';

        (function() {
            // Color Variables
            const purpleColor = '#836AF9',
                yellowColor = '#ffe800',
                cyanColor = '#28dac6',
                orangeColor = '#FF8132',
                orangeLightColor = '#FDAC34',
                oceanBlueColor = '#299AFF',
                greyColor = '#4F5D70',
                greyLightColor = '#EDF1F4',
                blueColor = '#2B9AFF',
                blueLightColor = '#84D0FF';

            let cardColor, headingColor, labelColor, borderColor, legendColor;

            if (isDarkStyle) {
                cardColor = config.colors_dark.cardColor;
                headingColor = config.colors_dark.headingColor;
                labelColor = config.colors_dark.textMuted;
                legendColor = config.colors_dark.bodyColor;
                borderColor = config.colors_dark.borderColor;
            } else {
                cardColor = config.colors.cardColor;
                headingColor = config.colors.headingColor;
                labelColor = config.colors.textMuted;
                legendColor = config.colors.bodyColor;
                borderColor = config.colors.borderColor;
            }

            // Doughnut Chart
            // --------------------------------------------------------------------

            const doughnutChart = document.getElementById('doughnutChart');
            if (doughnutChart) {
                const doughnutChartVar = new Chart(doughnutChart, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tablet', 'Mobile', 'Desktop'],
                        datasets: [{
                            data: [10, 10, 80],
                            backgroundColor: [cyanColor, orangeLightColor, config.colors.primary],
                            borderWidth: 0,
                            pointStyle: 'rectRounded'
                        }]
                    },
                    options: {
                        responsive: true,
                        animation: {
                            duration: 500
                        },
                        cutout: '50%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.labels || '',
                                            value = context.parsed;
                                        const output = ' ' + label + ' : ' + value + ' %';
                                        return output;
                                    }
                                },
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }

            // Radar Chart
            // --------------------------------------------------------------------

            const radarChart = document.getElementById('radarChart');
            if (radarChart) {
                // For radar gradient color
                const gradientBlue = radarChart.getContext('2d').createLinearGradient(0, 0, 0, 150);
                gradientBlue.addColorStop(0, 'rgba(85, 85, 255, 0.9)');
                gradientBlue.addColorStop(1, 'rgba(151, 135, 255, 0.8)');

                const gradientRed = radarChart.getContext('2d').createLinearGradient(0, 0, 0, 150);
                gradientRed.addColorStop(0, 'rgba(255, 85, 184, 0.9)');
                gradientRed.addColorStop(1, 'rgba(255, 135, 135, 0.8)');

                const radarChartVar = new Chart(radarChart, {
                    type: 'radar',
                    data: {
                        labels: ['عدد', 'الطلبيات المقبوله', 'الطلبيات المنتظره'],
                        datasets: [{
                                label: ' الطلبيات بالدولار ',
                                data: [{{ $usdOrdersCount_Count }}, {{ $usdOrdersAccept_Count }},
                                    {{ $usdOrdersWait_Count }}
                                ],
                                fill: true,
                                pointStyle: 'dash',
                                backgroundColor: gradientRed,
                                borderColor: 'transparent',
                                pointBorderColor: 'transparent'
                            },
                            {
                                label: ' الطلبيات بالليره   ',
                                data: [{{ $tlOrdersCount_Count }}, {{ $tlOrdersAccept_Count }},
                                    {{ $tlOrdersWait_Count }}
                                ],
                                fill: true,
                                pointStyle: 'dash',
                                backgroundColor: gradientBlue,
                                borderColor: 'transparent',
                                pointBorderColor: 'transparent'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 500
                        },
                        scales: {
                            r: {
                                ticks: {
                                    maxTicksLimit: 1,
                                    display: false,
                                    color: labelColor
                                },
                                grid: {
                                    color: borderColor
                                },
                                angleLines: {
                                    color: borderColor
                                },
                                pointLabels: {
                                    color: labelColor
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                rtl: isRtl,
                                position: 'top',
                                labels: {
                                    padding: 25,
                                    color: legendColor
                                }
                            },
                            tooltip: {
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }

            // Polar Chart
            // --------------------------------------------------------------------

            const polarChart = document.getElementById('polarChart');
            if (polarChart) {
                const polarChartVar = new Chart(polarChart, {
                    type: 'pie',
                    data: {
                        labels: ['الفروع', 'الاصناف', 'المبيعات', 'المصاريف', 'المديونيه', 'الطلبيات'],
                        datasets: [{
                            label: 'التقارير (عدد)',
                            backgroundColor: [purpleColor, yellowColor, orangeColor, oceanBlueColor,
                                greyColor, cyanColor
                            ],
                            data: [{{ $branchCount }}, {{ $itemCount }}, {{ $salesCount }},
                                {{ $outlayCount }}, {{ $debtorsCount }},
                                {{ $ordersCount }}
                            ],
                            borderWidth: 0
                        }]
                    },
                    // options: {
                    //     responsive: true,
                    //     maintainAspectRatio: false,
                    //     animation: {
                    //         duration: 300
                    //     },
                    //     scales: {
                    //         r: {
                    //             ticks: {
                    //                 display: false,
                    //                 color: labelColor
                    //             },
                    //             grid: {
                    //                 display: false
                    //             }
                    //         }
                    //     },
                    //     plugins: {
                    //         tooltip: {
                    //             // Updated default tooltip UI
                    //             rtl: isRtl,
                    //             backgroundColor: cardColor,
                    //             titleColor: headingColor,
                    //             bodyColor: legendColor,
                    //             borderWidth: 1,
                    //             borderColor: borderColor
                    //         },
                    //         legend: {
                    //             rtl: isRtl,
                    //             position: 'right',
                    //             labels: {
                    //                 usePointStyle: true,
                    //                 padding: 25,
                    //                 boxWidth: 8,
                    //                 boxHeight: 8,
                    //                 color: legendColor
                    //             }
                    //         }
                    //     }
                    // }
                });
            }
        })();
    </script>


@endsection

@section('content')


    {{--
    <div class="container">
        <div class="row">
            <!-- Card Border Shadow -->

            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="ti ti-truck ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">Fady</h4>
                        </div>
                        <p class="mb-1">مجموع الحساب بالليره : 88</p>
                        <p class="mb-1">مجموع الحساب بالدولار :: 77$</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i
                                        class='ti ti-git-fork ti-md'></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">Fady</h4>
                        </div>
                        <p class="mb-1">مجموع الحساب بالليره : 88</p>
                        <p class="mb-1">مجموع الحساب بالدولار :: 77$</p>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info"><i class='ti ti-clock ti-md'></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">Fady</h4>
                        </div>
                        <p class="mb-1">مجموع الحساب بالليره : 88</p>
                        <p class="mb-1">مجموع الحساب بالدولار :: 77$</p>
                        </p>
                    </div>
                </div>
            </div>
            <!--/ Card Border Shadow -->
        </div>
    </div> --}}


    <div class="row">


        <!-- Website Analytics -->
        <div class="col-lg-12 mb-4">
            <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                id="swiper-with-pagination-cards">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-white mb-0 mt-2">تفاصيل الفروع</h5>
                                {{-- <small>Total 28.5% Conversion Rate</small> --}}
                            </div>
                            <div class="row">
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                    <h6 class="text-white mt-0 mt-md-3 mb-3">تقارير</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ $bra_invantory_tl }}</p>
                                                    <p class="mb-0">المصاريف بالليرة</p>
                                                </li>

                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ $bra_invantory_Usd }}</p>
                                                    <p class="mb-0">المصاريف بالدولار</p>
                                                </li>

                                                {{-- <li class="d-flex align-items-center mb-2">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ $bra_salary }}</p>
                                                    <p class="mb-0">المبيعات</p>
                                                </li> --}}
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ $bra_boxUsd }}</p>
                                                    <p class="mb-0"> حساب الفرع بالدولار</p>
                                                </li>
                                                <li class="d-flex align-items-center mb-2">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                        {{ $bra_boxTl }}</p>
                                                    <p class="mb-0"> حساب الفرع بالليره</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    <img src="{{ asset('assets/img/illustrations/card-website-analytics-1.png') }}"
                                        alt="Website Analytics" width="170" class="card-website-analytics-img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-white mb-0 mt-2">تفاصيل الطلبيات </h5>
                                {{-- <small>Total 28.5% Conversion Rate</small> --}}
                            </div>
                            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                <h6 class="text-white mt-0 mt-md-3 mb-3">تقارير</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                    {{ $OrdersCountt }}</p>
                                                <p class="mb-0">عدد الطلبيات </p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                    {{ $OrdersAccept_Countt }}</p>
                                                <p class="mb-0"> عدد الطلبيات المقبوله </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                    {{ $OrdersWait_Countt }}</p>
                                                <p class="mb-0"> عدد الطلبيات في الانتظار </p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">
                                                    {{ $tlOrders_Countt }}</p>
                                                <p class="mb-0">عدد الطلبيات المرفوضه  </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                <img src="{{ asset('assets/img/illustrations/card-website-analytics-2.png') }}"
                                    alt="Website Analytics" width="170" class="card-website-analytics-img">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!--/ Website Analytics -->


        <!-- Radar Chart -->
        <div class="col-lg-6 col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"> تفاصيل الطلبيات</h5>
                </div>
                <div class="card-body pt-2">
                    <canvas class="chartjs" id="radarChart" style="height: 300px;" data-height="355"></canvas>
                </div>
            </div>
        </div>
        <!-- /Radar Chart -->

        <!-- Polar Area Chart -->
        <div class="col-lg-6 col-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">احصائيات عامه </h5>
                    <div class="card-header-elements ms-auto py-0 dropdown">
                        <button type="button" class="btn dropdown-toggle hide-arrow p-0" id="heat-chart-dd"
                            data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="heat-chart-dd">
                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 331px; padding-left: 16%;">
                    <canvas id="polarChart" class="chartjs" data-height="337"></canvas>
                </div>
            </div>
        </div>
        <!-- /Polar Area Chart -->

        {{-- <!-- Doughnut Chart -->
        <div class="col-lg-4 col-12 mb-4 ">
            <div class="card">
                <h5 class="card-header">User by Devices</h5>
                <div class="card-body">
                    <canvas id="doughnutChart" class="chartjs mb-4 col-lg-6 col-12"style="hieght:100px;"
                        data-height="350"></canvas>
                    <ul class="doughnut-legend d-flex justify-content-around ps-0 mb-2 pt-1">
                        <li class="ct-series-0 d-flex flex-column">
                            <h5 class="mb-0">Desktop</h5>
                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                style="background-color: rgb(102, 110, 232);width:35px; height:6px;"></span>
                            <div class="text-muted">80 %</div>
                        </li>
                        <li class="ct-series-1 d-flex flex-column">
                            <h5 class="mb-0">Tablet</h5>
                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                style="background-color: rgb(40, 208, 148);width:35px; height:6px;"></span>
                            <div class="text-muted">10 %</div>
                        </li>
                        <li class="ct-series-2 d-flex flex-column">
                            <h5 class="mb-0">Mobile</h5>
                            <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                style="background-color: rgb(253, 172, 52);width:35px; height:6px;"></span>
                            <div class="text-muted">10 %</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Doughnut Chart --> --}}



    </div>


@endsection
@section('script')
    {{-- <script>
        'use strict';

        (function() {
            // Color Variables
            const purpleColor = '#836AF9',
                yellowColor = '#ffe800',
                cyanColor = '#28dac6',
                orangeColor = '#FF8132',
                orangeLightColor = '#FDAC34',
                oceanBlueColor = '#299AFF',
                greyColor = '#4F5D70',
                greyLightColor = '#EDF1F4',
                blueColor = '#2B9AFF',
                blueLightColor = '#84D0FF';

            let cardColor, headingColor, labelColor, borderColor, legendColor;

            if (isDarkStyle) {
                cardColor = config.colors_dark.cardColor;
                headingColor = config.colors_dark.headingColor;
                labelColor = config.colors_dark.textMuted;
                legendColor = config.colors_dark.bodyColor;
                borderColor = config.colors_dark.borderColor;
            } else {
                cardColor = config.colors.cardColor;
                headingColor = config.colors.headingColor;
                labelColor = config.colors.textMuted;
                legendColor = config.colors.bodyColor;
                borderColor = config.colors.borderColor;
            }

            // Radar Chart
            // --------------------------------------------------------------------

            const radarChart = document.getElementById('radarChart');
            if (radarChart) {
                // For radar gradient color
                const gradientBlue = radarChart.getContext('2d').createLinearGradient(0, 0, 0, 150);
                gradientBlue.addColorStop(0, 'rgba(85, 85, 255, 0.9)');
                gradientBlue.addColorStop(1, 'rgba(151, 135, 255, 0.8)');

                const gradientRed = radarChart.getContext('2d').createLinearGradient(0, 0, 0, 150);
                gradientRed.addColorStop(0, 'rgba(255, 85, 184, 0.9)');
                gradientRed.addColorStop(1, 'rgba(255, 135, 135, 0.8)');

                const radarChartVar = new Chart(radarChart, {
                    type: 'radar',
                    data: {
                        labels: ['STA', 'STR', 'AGI', 'VIT', 'CHA', 'INT'],
                        datasets: [{
                                label: 'Donté Panlin',
                                data: [25, 59, 90, 81, 60, 82],
                                fill: true,
                                pointStyle: 'dash',
                                backgroundColor: gradientRed,
                                borderColor: 'transparent',
                                pointBorderColor: 'transparent'
                            },
                            {
                                label: 'Mireska Sunbreeze',
                                data: [40, 100, 40, 90, 40, 90],
                                fill: true,
                                pointStyle: 'dash',
                                backgroundColor: gradientBlue,
                                borderColor: 'transparent',
                                pointBorderColor: 'transparent'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 500
                        },
                        scales: {
                            r: {
                                ticks: {
                                    maxTicksLimit: 1,
                                    display: false,
                                    color: labelColor
                                },
                                grid: {
                                    color: borderColor
                                },
                                angleLines: {
                                    color: borderColor
                                },
                                pointLabels: {
                                    color: labelColor
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                rtl: isRtl,
                                position: 'top',
                                labels: {
                                    padding: 25,
                                    color: legendColor
                                }
                            },
                            tooltip: {
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            }
                        }
                    }
                });
            }

            // Polar Chart
            // --------------------------------------------------------------------

            const polarChart = document.getElementById('polarChart');
            if (polarChart) {
                const polarChartVar = new Chart(polarChart, {
                    type: 'polarArea',
                    data: {
                        labels: ['Africa', 'Asia', 'Europe', 'America', 'Antarctica', 'Australia'],
                        datasets: [{
                            label: 'Population (millions)',
                            backgroundColor: [purpleColor, yellowColor, orangeColor, oceanBlueColor,
                                greyColor, cyanColor
                            ],
                            data: [19, 17.5, 15, 13.5, 11, 9],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 500
                        },
                        scales: {
                            r: {
                                ticks: {
                                    display: false,
                                    color: labelColor
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                // Updated default tooltip UI
                                rtl: isRtl,
                                backgroundColor: cardColor,
                                titleColor: headingColor,
                                bodyColor: legendColor,
                                borderWidth: 1,
                                borderColor: borderColor
                            },
                            legend: {
                                rtl: isRtl,
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 25,
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    color: legendColor
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script> --}}

@endsection
