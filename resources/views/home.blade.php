@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $homeData->totalOrders }}</h3>

                    <p>Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $homeData->revenue }}</h3>

                    <p>Revenues</p>
                </div>
                <div class="icon">
                    <i class="ion fa-solid fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $homeData->totalProducts }}</h3>

                    <p>Products</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $homeData->totalUsers }}</h3>

                    <p>Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Reviewers</span>
                    <span class="info-box-number">
                        {{ $homeData->numberOfReviews }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fa-regular fa-comment"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Reviews</span>
                    <span class="info-box-number">{{ $homeData->numberOfRating }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fa-regular fa-star"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Average Rating</span>
                    <span class="info-box-number">{{ $homeData->averageRating }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="far fa-heart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">{{ $homeData->numberOfFavoritePr }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Last 7 days</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">${{ $homeData->revenueSevenDay }}</span>
                            <span>Revenue last 7 days</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($homeData->percentageForteenAgo >= 0)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> {{ $homeData->percentageForteenAgo }}%
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i> {{ $homeData->percentageForteenAgo }}%
                                </span>
                            @endif
                            <span class="text-muted">Since 14 days ago</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart-db" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> Order
                        </span>

                        <span>
                            <i class="fas fa-square text-gray"></i> Revenue
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Sales</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">${{ $homeData->revenueThisYear }}</span>
                            <span>Revenue this year</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($homeData->revenueWithYear->percentageMouth >= 0)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> {{ $homeData->revenueWithYear->percentageMouth }}%
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i> {{ $homeData->revenueWithYear->percentageMouth }}%
                                </span>
                            @endif
                            <span class="text-muted">Since last month</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart-db" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> This year
                        </span>

                        <span>
                            <i class="fas fa-square text-gray"></i> Last year
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Latest Orders</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($homeData->latestOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>
                                            <a
                                                href="{{ route('admin.order.detail', $order->sku) }}">{{ $order->sku }}</a>
                                        </td>
                                        <td>
                                            <div class="text-truncate-two">
                                                @foreach ($order->order_products as $pro)
                                                    {{ $pro->product->name . ', ' }}
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            @if ($order->status == 0)
                                                <span class="badge bg-danger">Pending</span>
                                            @elseif ($order->status == 1)
                                                <span class="badge bg-primary">Approved</span>
                                            @elseif ($order->status == 2)
                                                <span class="badge bg-success">Delivered</span>
                                            @else
                                                <span class="badge bg-warning">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $order->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <a href="{{ route('admin.order.list') }}">View All Orders</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-lg-6">
            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Best Seller</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Concentration</th>
                                    <th>Total sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($homeData->bestSeller as $prBestSeller)
                                    <tr>
                                        <td>{{ $prBestSeller->id }}</td>
                                        <td>
                                            <div class="text-truncate-two">
                                                <a href="{{ route('admin.product.detail', $prBestSeller->id) }}">{{ $prBestSeller->name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $prBestSeller->brand->name }}</td>
                                        <td>{{ $prBestSeller->concentration ? $prBestSeller->concentration->name : '' }}
                                        </td>
                                        <td>{{ $prBestSeller->quantity_sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->

                <div class="card-footer text-center">
                    <a href="{{ route('admin.product.list') }}">View All Products</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <!-- Left col -->
        <div class="col-lg-6">
            <!-- USERS LIST -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Latest Members</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="users-list clearfix d-flex flex-wrap">
                        @foreach ($homeData->latestUsers as $latestUser)
                            @php
                                $createdAt = \Carbon\Carbon::parse($latestUser->created_at);
                                $formattedDate = '';
                                if ($createdAt->isToday()) {
                                    $formattedDate = 'Today';
                                } elseif ($createdAt->isYesterday()) {
                                    $formattedDate = 'Yesterday';
                                } else {
                                    $formattedDate = $createdAt->format('d M'); // Định dạng ngày tháng
                                }
                            @endphp
                            <li class="text-center mb-3">
                                <img src="{{ displayImageOrDefault($latestUser->images, '/admin-layout/dist/img/user1-128x128.jpg') }}"
                                    alt="User Image" class="img-thumbnail rounded-circle border rounded"
                                    style="max-width: calc(128px + 0.5rem); aspect-ratio: 1 / 1; object-fit: cover;">
                                <div>
                                    <a class="users-list-name" href="#">{{ $latestUser->name }}</a>
                                    <span class="users-list-date">{{ $formattedDate }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="javascript:">View All Users</a>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
        <!--/.card -->

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recently Added Products</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach ($homeData->latestProducts as $latestProduct)
                            <li class="item">
                                <div class="product-img">
                                    <img src="{{ displayImageOrDefault($latestProduct->images, '/admin-layout/dist/img/default-150x150.png') }}"
                                        alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="{{ route('admin.product.detail', $latestProduct->id) }}"
                                        class="product-title">
                                        {{ $latestProduct->name }}
                                        <span class="badge badge-warning float-right">$
                                            {{ displayProductPriceOrDefault($latestProduct->quantities) }}
                                        </span>
                                    </a>
                                    <span class="product-description">
                                        {{ $latestProduct->brand->name }},
                                        {{ $latestProduct->concentration ? $latestProduct->concentration->name : '' }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="{{ route('admin.product.list') }}" class="uppercase">View All Products</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.item -->
        </div>
        <!--/.card -->
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('chart.data') }}',
            method: 'GET',
            success: function(response) {
                createChart(response);
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });

        function createChart(response) {
            'use strict'

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $salesChart = $('#sales-chart-db')
            // eslint-disable-next-line no-unused-vars
            var salesChart = new Chart($salesChart, {
                type: 'bar',
                data: {
                    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV',
                        'DEC'
                    ],
                    datasets: [{
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: [
                                response.revenueWithYear.jan,
                                response.revenueWithYear.feb,
                                response.revenueWithYear.mar,
                                response.revenueWithYear.apr,
                                response.revenueWithYear.may,
                                response.revenueWithYear.jun,
                                response.revenueWithYear.jul,
                                response.revenueWithYear.aug,
                                response.revenueWithYear.sep,
                                response.revenueWithYear.oct,
                                response.revenueWithYear.nov,
                                response.revenueWithYear.dec
                            ]
                        },
                        {
                            backgroundColor: '#ced4da',
                            borderColor: '#ced4da',
                            data: [
                                response.revenueWithYear.janLastYear,
                                response.revenueWithYear.febLastYear,
                                response.revenueWithYear.marLastYear,
                                response.revenueWithYear.aprLastYear,
                                response.revenueWithYear.mayLastYear,
                                response.revenueWithYear.junLastYear,
                                response.revenueWithYear.julLastYear,
                                response.revenueWithYear.augLastYear,
                                response.revenueWithYear.sepLastYear,
                                response.revenueWithYear.octLastYear,
                                response.revenueWithYear.novLastYear,
                                response.revenueWithYear.decLastYear
                            ]
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,

                                // Include a dollar sign in the ticks
                                callback: function(value) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }

                                    return '$' + value
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })

            var $stackedBarChartCanvas = $('#visitors-chart-db');

            var stackedBarChartData = {
                labels: [],
                datasets: [{
                    label: 'Dataset 1',
                    backgroundColor: '#007bff',
                    data: []
                }, {
                    label: 'Dataset 2',
                    backgroundColor: '#ced4da',
                    data: []
                }]
            };

            var stackedBarChartData = {
                labels: [],
                datasets: [{
                        label: 'order',
                        backgroundColor: '#007bff',
                        data: []
                    },
                    {
                        label: 'revenue',
                        backgroundColor: '#ced4da',
                        data: []
                    }
                ]
            };

            stackedBarChartData.labels = Object.keys(response.sevenDayData);
            stackedBarChartData.datasets[1].data = Object.values(response.sevenDayData).map(item => item
                .revenue);
            stackedBarChartData.datasets[0].data = Object.values(response.sevenDayData).map(item => item
                .totalOrder);

            var stackedBarChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: true
                },
                hover: {
                    mode: 'index',
                    intersect: true
                },
                legend: {
                    display: true
                },
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: $.extend({
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function(value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }

                                return value
                            }
                        }, ticksStyle)
                    }]
                }
            };

            var stackedBarChartConfig = {
                type: 'bar',
                data: stackedBarChartData,
                options: stackedBarChartOptions
            };

            var stackedBarChart = new Chart($stackedBarChartCanvas, stackedBarChartConfig);
        }
    </script>
@endsection
