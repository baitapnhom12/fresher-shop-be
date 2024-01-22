@extends('layouts.app')
@section('content')
    @if (session('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success('Success', {
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
            toastr.error('Error', {
                timeOut: 3000
            });
        </script>
    @endif
   
    <div>
        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Banners</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table id="table_banner" class="table table-striped projects">
                        <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banner as $key => $value)
                                <tr class="text-center">
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @foreach ($value->images as $image)
                                            <img src="{{ $image->path }}" alt="" width="50">
                                        @endforeach
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('banner.edit', $value->id) }}">
                                            <i class="fas fa-pencil-alt mr-2">
                                            </i>
                                            Edit
                                        </a><a class="btn btn-danger btn-sm mx-2" onclick="return confirmation(event);"
                                            href="{{ route('banner.delete', $value->id) }}">
                                            <i class="fas fa-trash mr-2">
                                            </i>
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <script>
        new DataTable('#table_banner', {
            order: [0],
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: ['csv', 'excel', 'pdf', 'print'],
        })

        function confirmation(event) {
            event.preventDefault();
            var url = event.currentTarget.getAttribute('href');
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = url;
                    }
                });
        }
    </script>
@endsection
