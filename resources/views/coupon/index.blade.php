@extends('layouts.app')
@section('content')
    <div class="col-12 d-flex justify-content-end mt-n4">
        <form method="get" id="send_coupon">
            <button type="submit" class="btn btn-primary mr-3 btn_send_coupon" data-toggle="modal">
                <i class="fa-solid fa-paper-plane"></i></i> Send Mail
            </button>
        </form>
        <button id="add" type="button" class="btn btn-success" data-toggle="modal">
            <i class="fa-solid fa-plus"></i> Add
        </button>
    </div>
    <!-- Modal add-->
    <div id="addcouponModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Coupon</h5>
                </div>
                <form id="add_coupon_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name Sku</label>
                            <input type="text" name="sku" id="sku" class="form-control">
                            <small id="error_coupon_sku" class="form-text text-danger"></small>
                            <div class="my-2">
                                <label for="">Usage Count</label>
                                <input type="text" name="usage_count" id="usage_count" class="form-control">
                                <small id="error_coupon_usage_count" class="form-text text-danger"></small>
                            </div>
                            <div class="my-2">
                                <label for="">Discount</label>
                                <input type="text" name="discount" id="discount" class="form-control">
                                <small id="error_coupon_discount" class="form-text text-danger"></small>
                            </div>
                            <div class="my-2">
                                <label for="">Expired At</label>
                                <input type="datetime-local" name="expired_at" id="expired_at" class="form-control">
                                <small id="error_coupon_expired_at" class="form-text text-danger"></small>
                            </div>
                            <label for="">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">Choose Type</option>
                                <option value="1">Discount</option>
                                <option value="0">Price</option>

                            </select>
                            <small id="error_coupon_type" class="form-text text-danger"></small>
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
    <div id="updatecouponModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Coupon</h5>
                </div>
                <form id="update_coupon_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="id" id="id" class="form-control id_coupon" hidden>
                            <div class="form-group">
                                <label for="">Name Sku</label>
                                <input type="text" name="sku" id="sku" class="form-control sku_coupon"
                                    readonly>
                                <small id="error_coupon_sku_update" class="form-text text-danger"></small>
                                <div class="my-2">
                                    <label for="">Usage Count</label>
                                    <input type="text" name="usage_count" id="usage_count"
                                        class="form-control usage_count_coupon">
                                    <small id="error_coupon_usage_count_update" class="form-text text-danger"></small>
                                </div>
                                <div class="my-2">
                                    <label for="">Discount</label>
                                    <input type="text" name="discount" id="discount"
                                        class="form-control discount_coupon">
                                    <small id="error_coupon_discount_update" class="form-text text-danger"></small>
                                </div>
                                <div class="my-2">
                                    <label for="">Expired At</label>
                                    <input type="datetime-local" name="expired_at" id="expired_at"
                                        class="form-control expired_at_coupon" step="1">
                                    <small id="error_coupon_expired_at_update" class="form-text text-danger"></small>
                                </div>
                                <label for="">Type</label>
                                <select class="form-control type" name="type" @readonly(true)>
                                    <option value="">Choose Type</option>
                                    <option value="1">Discount</option>
                                    <option value="0">Price</option>
                                </select>
                                <small id="error_coupon_type_update" class="form-text text-danger"></small>
                            </div>
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
                    <h3 class="card-title">Coupons</h3>

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
                    <table id="table_coupons" class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Sku</th>
                                <th>Usage Count</th>
                                <th>Discount</th>
                                <th>Expired At</th>
                                <th>Type</th>
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
            $('#addcouponModal').modal('show');
        })
        $(document).on('click', '#close_add', function(e) {
            $('#addcouponModal').modal('hide');
        })
    </script>
    <script>
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //   get_coupons
        var table = $('#table_coupons').DataTable({
            ajax: "{{ route('coupon.list') }}",
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
                    data: 'sku',
                },
                {
                    className: 'text-center',
                    data: 'usage_count',
                },
                {
                    className: 'text-center',
                    data: 'discount',
                },
                {
                    className: 'text-center',
                    data: 'expired_at',

                },
                {
                    className: 'text-center',
                    data: null,
                    render: function(data, type, row) {
                        if (row.type == "1") {
                            return `<span class="badge badge-success">Discount</span>`;
                        } else {
                            return `<span class="badge badge-primary">Price</span>`;
                        }
                    },
                },
                {
                    className: 'text-center',
                    "data": null,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button type="button" id="delete_coupon" data-id="${row.id}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                                <button type="button" id="show_coupon" data-id="${row.id}" class="btn btn-info btn-sm mx-2">
                                        <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>`
                    }
                },
            ]
        });
        // add_coupons
        $(document).ready(function() {
            $("#add_coupon_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('coupon.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#add_coupon_form")[0].reset();
                            $("#error_coupon_sku").html(null);
                            $("#error_coupon_usage_count").html(null);
                            $("#error_coupon_discount").html(null);
                            $("#error_coupon_expired_at").html(null);
                            $("#error_coupon_type").html(null);
                            $("#addcouponModal").modal('hide');
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        console.log(error);
                        if (error.responseJSON.errors.sku) {
                            $("#error_coupon_sku").html(error.responseJSON.errors.sku);
                        } else {
                            $("#error_coupon_sku").html(null);
                        }
                        if (error.responseJSON.errors.usage_count) {
                            $("#error_coupon_usage_count").html(error.responseJSON.errors
                                .usage_count);
                        } else {
                            $("#error_coupon_usage_count").html(null);
                        }
                        if (error.responseJSON.errors.discount) {
                            $("#error_coupon_discount").html(error.responseJSON.errors
                                .discount);
                        } else {
                            $("#error_coupon_discount").html(null);
                        }
                        if (error.responseJSON.errors.expired_at) {
                            $("#error_coupon_expired_at").html(error.responseJSON.errors
                                .expired_at);
                        } else {
                            $("#error_coupon_expired_at").html(null);
                        }
                        if (error.responseJSON.errors.type) {
                            $("#error_coupon_type").html(error.responseJSON.errors.type);
                        } else {
                            $("#error_coupon_type").html(null);
                        }
                    }
                })
            })
        });
        // show coupons
        $(document).on('click', '#show_coupon', function(e) {
            $("#error_coupon_sku_update").html(null);
            $("#error_coupon_usage_count_update").html(null);
            $("#error_coupon_discount_update").html(null);
            $("#error_coupon_expired_at_update").html(null);
            $("#error_coupon_type_update").html(null);
            $('#updatecouponModal').modal('show');
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('coupon.show') }}',
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    $(".id_coupon").val(response.data.id);
                    $(".sku_coupon").val(response.data.sku);
                    $(".discount_coupon").val(response.data.discount);
                    $(".usage_count_coupon").val(response.data.usage_count);
                    $(".expired_at_coupon").val(response.data.expired_at);
                    $(".type").val(response.data.type);
                }
            });
        })
        $(document).on('click', '#close_update', function(e) {
            $('#updatecouponModal').modal('hide');
        })
        // update coupon
        $(document).ready(function() {
            $("#update_coupon_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('coupon.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#update_coupon_form")[0].reset();
                            $("#updatecouponModal").modal('hide');
                            swal("Update Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error.responseJSON.errors.sku) {
                            $("#error_coupon_sku_update").html(error.responseJSON.errors.sku);
                        } else {
                            $("#error_coupon_sku_update").html(null);
                        }
                        if (error.responseJSON.errors.usage_count) {
                            $("#error_coupon_usage_count_update").html(error.responseJSON.errors
                                .usage_count);
                        } else {
                            $("#error_coupon_usage_count_update").html(null);
                        }
                        if (error.responseJSON.errors.discount) {
                            $("#error_coupon_discount_update").html(error.responseJSON.errors
                                .discount);
                        } else {
                            $("#error_coupon_discount_update").html(null);
                        }
                        if (error.responseJSON.errors.expired_at) {
                            $("#error_coupon_expired_at_update").html(error.responseJSON.errors
                                .expired_at);
                        } else {
                            $("#error_coupon_expired_at_update").html(null);
                        }
                        if (error.responseJSON.errors.type) {
                            $("#error_coupon_type_update").html(error.responseJSON.errors
                                .type);
                        } else {
                            $("#error_coupon_type_update").html(null);
                        }
                    }
                })
            })
        });
        // delete_coupons
        $(document).on('click', '#delete_coupon', function(e) {
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
                            url: '{{ route('coupon.delete') }}',
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
        // send coupon
        $(document).ready(function() {
            $("#send_coupon").submit(function(e) {
                e.preventDefault();
                $('.btn_send_coupon').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_send_coupon').attr('disabled')
                $.ajax({
                    type: 'GET',
                    url: '{{ route('coupon.send') }}',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            $('.btn_send_coupon').find('.fa-spinner').remove();
                            $('.btn_send_coupon').removeAttr('disabled');
                            swal("Send Mail Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            $('.btn_send_coupon').find('.fa-spinner').remove();
                            $('.btn_send_coupon').removeAttr('disabled');
                            swal("Error", {
                                icon: "error",
                            })
                        }
                       
                    }
                })
            })
        });
    </script>
@endsection
