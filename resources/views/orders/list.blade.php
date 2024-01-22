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
            toastr.error("{{ session('error') }}", 'Error!', {
                timeOut: 3000
            });
        </script>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Orders DataTable</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table p-0 table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Code</th>
                            <th>Products</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Account Order</th>
                            <th>Receiver</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id ?? '' }}</td>
                                <td>{{ $order->code ?? '' }}</td>
                                <td>
                                    <div class="text-truncate-two">
                                        @foreach ($order->products as $product)
                                            {{ $product->name . ': ' }}{{ $product->size . ', ' }}
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $order->orderDate ?? '' }}</td>
                                <td class="text-center">
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
                                <td class="text-center">
                                    {{ $order->total }}
                                </td>
                                <td>
                                    {{ $order->accountOrder }}
                                </td>
                                <td>
                                    {{ $order->receiver }}
                                </td>
                                <td class="text-center">
                                    @if ($order->paymentMethod == 0)
                                        <span class="badge bg-danger">COD</span>
                                    @elseif ($order->paymentMethod == 1)
                                        <span class="badge bg-success">Bank transfer in advance</span>
                                    @else
                                        <span class="badge bg-warning">Unknown</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($order->payment->status == 0)
                                        <span class="badge bg-danger">Unpaided</span>
                                    @elseif ($order->payment->status == 1)
                                        <span class="badge bg-success">Paided</span>
                                    @else
                                        <span class="badge bg-warning">Unknown</span>
                                    @endif
                                </td>
                                <td class="project-actions text-center py-0 align-middle">
                                    <a class="btn btn-success btn-sm detail-product "
                                        href="{{ route('admin.order.detail', $order->code) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "ordering": false,
                "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
