@extends('layouts.app')
@section('content')
<div class="col-12 d-flex justify-content-end mt-n4">
        <button id="add" type="button" class="btn btn-success" data-toggle="modal">
            <i class="fa-solid fa-plus"></i> Add
        </button>
    </div>
    <!-- Modal add-->
    <div id="addinformationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Information</h5>
                </div>
                <form id="add_information_form">
                    @csrf
                   
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            <small id="error_name_add" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <input type="text" name="content" id="content" class="form-control" placeholder="Content">
                            <small id="error_content_add" class="form-text text-danger"></small>
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
    <div id="updateinformationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Information</h5>
                </div>
                <form id="update_information_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="id" id="id" class="form-control id_information" hidden>
                            <input type="text" name="name" id="name" class="form-control name_information">
                            <small id="error_name_update" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <input type="text" name="content" id="content" class="form-control content_information" placeholder="Content">
                            <small id="error_content_update" class="form-text text-danger"></small>
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
                    <h3 class="card-title">Informations</h3>

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
                    <table id="table_informations" class="table table-striped projects">
                        <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Content</th>
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
            $('#addinformationModal').modal('show');
        })
        $(document).on('click', '#close_add', function(e) {
            $('#addinformationModal').modal('hide');
        })

    </script>
    <script>
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //   get_informations
        var table = $('#table_informations').DataTable({
            ajax: "{{ route('information.list') }}",
            order: [0],
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: ['csv', 'excel', 'pdf', 'print'],
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
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data.content.substr(0, 120)
                    }
                },
               
                {
                    "data": null,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<button type="button" id="delete_information" data-id="${row.id}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                                <button type="button" id="show_information" data-id="${row.id}" class="btn btn-info btn-sm mx-2">
                                        <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>`
                    }
                },
            ]
        });
        // add_informations
        $(document).ready(function() {
            $("#add_information_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('information.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#add_information_form")[0].reset();
                            $("#error_name_add").html(null);
                            $("#error_content_add").html(null);
                            $("#addinformationModal").modal('hide');
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error.responseJSON.errors.name) {
                            $("#error_name_add").html(error.responseJSON.errors.name);
                        } else {
                            $("#error_name_add").html(null);
                        }
                        if (error.responseJSON.errors.content) {
                            $("#error_content_add").html(error.responseJSON.errors.content);
                        } else {
                            $("#error_content_add").html(null);
                        }

                    }
                })
            })
        });
        // show informations
        $(document).on('click', '#show_information', function(e) {
            $("#error_name_update").html(null);
            $("#error_content_update").html(null);
            $('#updateinformationModal').modal('show');
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('information.show') }}',
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    console.log(response.data)
                    $(".id_information").val(response.data.id);
                    $(".name_information").val(response.data.name);
                    $(".email_information").val(response.data.email);
                    $(".content_information").val(response.data.content);
                }
            });
        })
        $(document).on('click', '#close_update', function(e) {
            $('#updateinformationModal').modal('hide');
        })
        // update information
        $(document).ready(function() {
            $("#update_information_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('information.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#update_information_form")[0].reset();
                            $("#error_information_update").html(null);
                            $("#updateinformationModal").modal('hide');
                            swal("Update Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error.responseJSON.errors.name) {
                            $("#error_name_update").html(error.responseJSON.errors.name);
                        } else {
                            $("#error_name_update").html(null);
                        }
                        if (error.responseJSON.errors.content) {
                            $("#error_content_update").html(error.responseJSON.errors.content);
                        } else {
                            $("#error_content_update").html(null);
                        }
                    }
                })
            })
        });
        // delete_informations
        $(document).on('click', '#delete_information', function(e) {
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
                            url: '{{ route('information.delete') }}',
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
