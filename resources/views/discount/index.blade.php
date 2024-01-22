@extends('layouts.app')
@section('content')
    <div class="col-12 d-flex justify-content-end mt-n4">
        <button id="add" type="button" class="btn btn-success" data-toggle="modal">
            <i class="fa-solid fa-plus"></i> Add
        </button>
    </div>
    <!-- Modal add-->
    <div id="adddiscountModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create discount</h5>
                </div>
                <form id="add_discount_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            <small id="error_discount_name" class="form-text text-danger"></small>
                            <div class="my-3">
                                <input type="text" name="percent" id="percent" class="form-control"
                                    placeholder="Percent">
                                <small id="error_discount_percent" class="form-text text-danger"></small>
                            </div>
                            <select class="form-control" id="active" name="active">
                                <option value="1">Active</option>
                                <option value="2">Hidden</option>
                            </select>
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
    <div id="updatediscountModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Discount</h5>
                </div>
                <form id="update_discount_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="id" id="id" class="form-control id_discount" hidden>
                            <input type="text" name="name" id="name" class="form-control name_discount">
                            <small id="error_discount_nameupdate" class="form-text text-danger"></small>
                            <div class="my-3">
                                <input type="text" name="percent" id="percent" class="form-control percent_discount">
                                <small id="error_discount_percentupdate" class="form-text text-danger"></small>
                            </div>
                            <select class="form-control active_discount_update" id="active" name="active">
                                <option value="1">Active</option>
                                <option value="2">Hidden</option>
                            </select>
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
                    <h3 class="card-title">Discounts</h3>

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
                    <table id="table_discounts" class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Percent</th>
                                <th>Status</th>
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
            $('#adddiscountModal').modal('show');
        })
        $(document).on('click', '#close_add', function(e) {
            $('#adddiscountModal').modal('hide');
        })
    </script>
    <script>
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //   get_discounts
        var table = $('#table_discounts').DataTable({
            ajax: "{{ route('discount.list') }}",
            order: [0],
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: ['csv', 'excel', 'pdf', 'print'],
            columns: [{
                    className: 'text-center',
                    data: 'id',
                },
                {
                    className: 'text-center',
                    data: 'name',
                },
                {
                    className: 'text-center',
                    data: 'percent',
                },
                {
                    className: 'text-center',
                    "data": null,
                    render: function(data, type, row) {
                        if (row.active == "1") {
                            return `<span class="badge badge-success">Active</span>`;
                        } else {
                            return `<span class="badge badge-dark">Hidden</span>`;
                        }
                    }
                },
                {
                    className: 'text-center',
                    "data": null,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button type="button" id="delete_discount" data-id="${row.id}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                                <button type="button" id="show_discount" data-id="${row.id}" class="btn btn-info btn-sm mx-2">
                                        <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>`
                    }
                },
            ]
        });
        // add_discounts
        $(document).ready(function() {
            $("#add_discount_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('discount.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#add_discount_form")[0].reset();
                            $("#error_discount_name").html(null);
                            $("#error_discount_percent").html(null);
                            $("#adddiscountModal").modal('hide');
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error.responseJSON.errors.name) {
                            $("#error_discount_name").html(error.responseJSON.errors.name);
                        }
                        else{
                            $("#error_discount_name").html(null);
                        }
                        if (error.responseJSON.errors.percent) {
                            $("#error_discount_percent").html(error.responseJSON.errors.percent);
                        }
                        else{
                            $("#error_discount_percent").html(null);
                        }
                        
                    }
                })
            })
        });
        // show discounts
        $(document).on('click', '#show_discount', function(e) {
            $("#error_discount_nameupdate").html(null);
            $("#error_discount_percentupdate").html(null);
            $('#updatediscountModal').modal('show');
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('discount.show') }}',
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    console.log(response.data)
                    $(".id_discount").val(response.data.id);
                    $(".name_discount").val(response.data.name);
                    $(".percent_discount").val(response.data.percent);
                    $(".active_discount_update").val(response.data.active);
                }
            });
        })
        $(document).on('click', '#close_update', function(e) {
            $('#updatediscountModal').modal('hide');
        })
        // update discount
        $(document).ready(function() {
            $("#update_discount_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('discount.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#update_discount_form")[0].reset();
                            $("#error_discount_update").html(null);
                            $("#updatediscountModal").modal('hide');
                            swal("Update Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            if (error.responseJSON.errors.name) {
                            $("#error_discount_nameupdate").html(error.responseJSON.errors.name);
                        }
                        else{
                            $("#error_discount_name").html(null);
                        }
                        if (error.responseJSON.errors.percent) {
                            $("#error_discount_percentupdate").html(error.responseJSON.errors.percent);
                        }
                        else{
                            $("#error_discount_percent").html(null);
                        }

                        }
                    }
                })
            })
        });
        // delete_discounts
        $(document).on('click', '#delete_discount', function(e) {
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
                            url: '{{ route('discount.delete') }}',
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
