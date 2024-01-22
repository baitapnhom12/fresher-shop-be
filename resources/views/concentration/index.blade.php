@extends('layouts.app')
@section('content')
    <div class="col-12 d-flex justify-content-end mt-n4">
        <button id="add" type="button" class="btn btn-success" data-toggle="modal">
            <i class="fa-solid fa-plus"></i> Add
        </button>
    </div>
    <!-- Modal add-->
    <div id="addconcentrationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Concentration</h5>
                </div>
                <form id="add_concentration_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            <small id="error_concentration_add" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="close_add" type="button" class="btn btn-secondary">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal update-->
    <div id="updateconcentrationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Concentration</h5>
                </div>
                <form id="update_concentration_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="id" id="id" class="form-control id_concentration"
                                hidden>
                            <input type="text" name="name" id="name" class="form-control name_concentration">
                            <small id="error_concentration_update" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="close_update" type="button" class="btn btn-secondary">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Concentrations</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body py-0">
                    <table id="table_concentrations" class="table table-striped projects">
                        <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <script>
        $(document).on('click', '#add', function(e) {
            $('#addconcentrationModal').modal('show');
        })
        $(document).on('click', '#close_add', function(e) {
            $('#addconcentrationModal').modal('hide');
        })
    </script>
    <script>
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //   get_concentrations
        var table = $('#table_concentrations').DataTable({
            ajax: "{{ route('concentration.list') }}",
            order: [0],
            dom: 'Bfrtip',
            buttons: ['csv', 'excel', 'pdf', 'print'],
            lengthChange: false,
            columns: [{
                    data: 'id',
                    className: 'text-center'
                },
                {
                    data: 'name',
                    className: 'text-center'
                },
                {
                    "data": null,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<button type="button" id="delete_concentration" data-id="${row.id}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                                <button type="button" id="show_concentration" data-id="${row.id}" class="btn btn-info btn-sm mx-2">
                                        <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>`
                    }
                },
            ]
        });
        // add_concentrations
        $(document).ready(function() {
            $("#add_concentration_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('concentration.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#add_concentration_form")[0].reset();
                            $("#error_concentration_add").html(null);
                            $("#addconcentrationModal").modal('hide');
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            $("#error_concentration_add").html(error.responseJSON.errors.name);
                        }
                    }
                })
            })
        });
        // show concentrations
        $(document).on('click', '#show_concentration', function(e) {
            $("#error_concentration_update").html(null);
            $('#updateconcentrationModal').modal('show');
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('concentration.show') }}',
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    console.log(response.data)
                    $(".id_concentration").val(response.data.id);
                    $(".name_concentration").val(response.data.name);
                }
            });
        })
        $(document).on('click', '#close_update', function(e) {
            $('#updateconcentrationModal').modal('hide');
        })
        // update concentration
        $(document).ready(function() {
            $("#update_concentration_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('concentration.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#update_concentration_form")[0].reset();
                            $("#error_concentration_update").html(null);
                            $("#updateconcentrationModal").modal('hide');
                            swal("Update Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            $("#error_concentration_update").html(error.responseJSON.errors
                                .name);
                        }
                    }
                })
            })
        });
        // delete_concentrations
        $(document).on('click', '#delete_concentration', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: 'POST',
                            url: '{{ route('concentration.delete') }}',
                            data: {
                                id: id
                            },
                            success: (response) => {
                                if (response.status == true) {
                                    table.ajax.reload();
                                    swal("Delete Success", {
                                        icon: "success",
                                    });
                                }
                            },
                            error: (error) => {
                                if (error) {
                                    swal("Delete Error", {
                                        icon: "error",
                                    });
                                }
                            }
                        })
                    }
                });
        })
    </script>
@endsection
