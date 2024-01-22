@extends('layouts.app')
@section('content')
    <!-- Modal update-->
    <div id="updatefeedbackModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reply</h5>
                </div>
                <form id="update_feedback_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="id" id="id" class="form-control id_feedback" hidden>
                            <input type="text" name="email" id="email" class="form-control email_feedback" hidden>
                            <small id="error_feedback_update" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <textarea name="reply" id="reply" cols="30" rows="10" class="reply"></textarea>
                            <small id="error_reply" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="close_update" type="button" class="btn btn-secondary">Close</button>
                        <button type="submit" class="btn btn-primary btn_submit">Save</button>
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
                    <h3 class="card-title">Feedbacks</h3>

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
                    <table id="table_feedbacks" class="table table-striped projects">
                        <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
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
            $('#addfeedbackModal').modal('show');
        })
        $(document).on('click', '#close_add', function(e) {
            $('#addfeedbackModal').modal('hide');
        })
        $(".reply").summernote()
    </script>
    <script>
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //   get_feedbacks
        var table = $('#table_feedbacks').DataTable({
            ajax: "{{ route('feedback.list') }}",
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
                    data: 'email',
                    className: 'text-center'
                },
                {
                    "data": null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data.message.substr(0, 50)
                    }
                },
                {
                    className: 'text-center',
                    "data": null,
                    render: function(data, type, row) {
                        if (row.reply == null) {
                            return `<span class="badge badge-success">UnReply</span>`;
                        } else {
                            return `<span class="badge badge-dark">Reply</span>`;
                        }
                    }
                },
                {
                    "data": null,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<button type="button" id="delete_feedback" data-id="${row.id}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                                <button type="button" id="show_feedback" data-id="${row.id}" class="btn btn-info btn-sm mx-2">
                                        <i class="fas fa-pencil-alt mr-2"></i>Reply
                                </button>`
                    }
                },
            ]
        });

        // show feedbacks
        $(document).on('click', '#show_feedback', function(e) {
            $("#error_reply").html(null);
            $('#updatefeedbackModal').modal('show');
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('feedback.show') }}',
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    console.log(response.data)
                    $(".id_feedback").val(response.data.id);
                    $(".email_feedback").val(response.data.email);
                }
            });
        })
        $(document).on('click', '#close_update', function(e) {
            $('#updatefeedbackModal').modal('hide');
        })
        // update feedback
        $(document).ready(function() {
            $("#update_feedback_form").submit(function(e) {
                e.preventDefault();
                $('.btn_submit').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_submit').attr('disabled')
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('feedback.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#update_feedback_form")[0].reset();
                            $("#error_reply").html(null);
                            $("#updatefeedbackModal").modal('hide');
                            swal("Update Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            $("#error_feedback_update").html(error.responseJSON.errors.name);
                            $('.btn_submit').find('.fa-spinner').remove();
                            $('.btn_submit').removeAttr('disabled');
                        }
                        if (error.responseJSON.errors.reply) {
                            $("#error_reply").html(error.responseJSON.errors.reply);
                        } else {
                            $("#error_reply").html(null);
                        }
                    }
                })
            })
        });
        // delete_feedbacks
        $(document).on('click', '#delete_feedback', function(e) {
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
                            url: '{{ route('feedback.delete') }}',
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
