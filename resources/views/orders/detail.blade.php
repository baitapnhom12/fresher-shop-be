@extends('layouts.app')
@section('content')
    @if (session('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success("{{ session('success') }}", 'Success!', {
                timeOut: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.error("{{ session('error') }}", 'Success!', {
                timeOut: 3000
            });
        </script>
    @endif

    <div class="col-12">
        <!-- Main content -->
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-globe"></i> HYBRID TECHNOLOGIES VIETNAM CO., LTD.
                        <small class="float-right">Date: {{ date('Y-m-d') }}</small>
                    </h4>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong>HAN Talent Academy</strong><br>
                        12th Floor, Central Point Building 219 Trung Kinh Street<br>
                        Yen Hoa Ward, Cau Giay District, Hanoi City, Viet Nam<br>
                        Email: fresher@hybrid-technologies.com.vn
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>{{ $order->receiver }}</strong><br>
                        {{ $order->shippingAddress }}<br>
                        Phone: {{ $order->phone }}<br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Invoice</b><br>
                    <b>Order ID:</b> {{ $order->id }}<br>
                    <b>Order Code:</b> {{ $order->code }}<br>
                    <b>Order Date:</b> {{ $order->orderDate }}<br>
                    <b>Order Status:</b>
                    @if ($order->status == 0)
                        <span class="badge bg-danger">Pending</span>
                    @elseif ($order->status == 1)
                        <span class="badge bg-primary">Approved</span>
                    @elseif ($order->status == 2)
                        <span class="badge bg-success">Delivered</span>
                    @else
                        <span class="badge bg-warning">Unknown</span>
                    @endif
                    <br>
                    <b>Account:</b> {{ $order->accountOrder }}
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row mt-3 mb-3">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr>
                                    <td>{{ $product->productId }}</td>
                                    <td><img src="{{ $product->image }}" alt="{{ $product->name }}" width="50px"
                                            height="50px">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->size }}</td>
                                    <td>${{ $product->price }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>${{ $product->price * $product->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                    <p class="lead">Payment Method: @if ($order->paymentMethod == 0)
                            <span class="badge bg-danger">COD</span>
                        @elseif ($order->paymentMethod == 1)
                            <span class="badge bg-success">Bank transfer in advance</span>
                        @else
                            <span class="badge bg-warning">Unknown</span>
                        @endif
                    </p>
                    @if ($order->paymentMethod == 0 && !$order->payment)
                        <img width="auto" height="60px" src="/admin-layout/dist/img/credit/cod_image.png" alt="COD">
                    @endif

                    @if ($order->paymentMethod == 0 && $order->payment)
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Provider:</th>
                                    <td>{{ $order->payment->provider }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>${{ $order->payment->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($order->payment->status == 0)
                                            <span class="badge bg-danger">Unpaided</span>
                                        @elseif ($order->payment->status == 1)
                                            <span class="badge bg-success">Paided</span>
                                        @else
                                            <span class="badge bg-warning">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif

                    @if ($order->paymentMethod == 1 && $order->payment)
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Provider:</th>
                                    <td>{{ $order->payment->provider }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Account Number:</th>
                                    <td>{{ $order->payment->accountNumber }}</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>${{ $order->payment->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($order->payment->status == 0)
                                            <span class="badge bg-danger">Unpaided</span>
                                        @elseif ($order->payment->status == 1)
                                            <span class="badge bg-success">Paided</span>
                                        @else
                                            <span class="badge bg-warning">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif
                </div>
                <!-- /.col -->
                <div class="col-6">
                    <p class="lead">Amount Due {{ date('Y-m-d') }}</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td>${{ $order->subTotal }}
                                </td>
                            </tr>
                            <tr>
                                <th>Discount:</th>
                                <td>${{ ($order->discount / 100) * $order->subTotal }}</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td>${{ $order->shippingFee }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>${{ $order->total }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12">
                    <a href="{{ route('admin.order.detail', $order->code) }}" rel="noopener" target="_blank"
                        class="btn btn-default" id="printButton"><i class="fas fa-print"></i> Print</a>

                    @if ($order->paymentMethod == 0 && $order->payment && $order->payment->status == 0 && $order->status == 1)
                        <a type="button" class="btn btn-success float-right"
                            href="{{ route('admin.order.paided', $order->code) }}"><i class="far fa-credit-card"></i>
                            Delivered
                        </a>
                    @endif

                    @if ($order->paymentMethod == 1 && $order->payment && $order->payment->status == 1 && $order->status == 1)
                        <a type="button" class="btn btn-success float-right"
                            href="{{ route('admin.order.paided', $order->code) }}"><i class="far fa-credit-card"></i>
                            Delivered
                        </a>
                    @endif

                    @if ($order->status == 0)
                        <a type="button" class="btn btn-primary float-right" style="margin-right: 5px;"
                            href="{{ route('admin.order.approved', $order->code) }}">
                            <i class="fa-regular fa-square-check"></i> Approve
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.invoice -->
    </div>

    <script>
        document.getElementById("printButton").addEventListener("click", function(event) {
            event.preventDefault();

            var newWindow = window.open(this.href, '_blank');

            newWindow.onload = function() {
                newWindow.print();
            };
        });
    </script>
@endsection
