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
                    <h3 class="card-title">Posts</h3>

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
                    <table id="table_posts" class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Categories</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Create At</th>
                                <th>Popular</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $value)
                                <tr class="text-center">
                                    <td>{{ $value->id }}</td>
                                    <td><img src="{{ $value->image }}" alt="" width="35"></td>
                                    <td>{{ $value->title }}</td>
                                    <td>
                                        @foreach ($value->articlePost as $article)
                                            <span for="">{{ $article->name }}</span><br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($value->active == $enumactive)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-dark">Hidden</span>
                                        @endif
                                    </td>
                                    <td>{{ $value->author }}</td>
                                    <td>{{ $value->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        @if ($value->popular == $enumpopular)
                                            <span class="badge badge-warning">Popular</span>
                                        @else
                                            <span class="badge badge-primary">Unpopular</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('post.edit', $value->id) }}">
                                            <i class="fas fa-pencil-alt mr-2">
                                            </i>
                                            Edit
                                        </a><a class="btn btn-danger btn-sm mx-2" onclick="return confirmation(event);"
                                            href="{{ route('post.delete', $value->id) }}">
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
        new DataTable('#table_posts', {
            order: [0],
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: ['csv', 'excel', 'pdf', 'print']
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
