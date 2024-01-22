@extends('layouts.app')
@section('content')
<div class="col-12 d-flex justify-content-end mt-n4">
        <button id="add" type="button" class="btn btn-success" data-toggle="modal">
            <i class="fa-solid fa-plus"></i> Add
        </button>
    </div>
    <!-- Modal add-->
    <div id="addquestionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Question</h5>
                </div>
                <form id="add_question_form">
                    @csrf
                   
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            <small id="error_name_add" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <textarea name="content" id="content" cols="30" rows="10" class="content_add"></textarea>
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
    <div id="updatequestionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Question</h5>
                </div>
                <form id="update_question_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="id" id="id" class="form-control id_question" hidden>
                            <input type="text" name="name" id="name" class="form-control name_question">
                            <small id="error_name_update" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <textarea name="content" id="content" cols="30" rows="10" class="content_question"></textarea>
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
                    <h3 class="card-title">Questions</h3>

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
                    <table id="table_questions" class="table table-striped projects">
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
            $('#addquestionModal').modal('show');
        })
        $(document).on('click', '#close_add', function(e) {
            $('#addquestionModal').modal('hide');
        })
        $(".content_add").summernote()
    </script>
    <script>
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //   get_questions
        var table = $('#table_questions').DataTable({
            ajax: "{{ route('question.list') }}",
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
                        return `<button type="button" id="delete_question" data-id="${row.id}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                                <button type="button" id="show_question" data-id="${row.id}" class="btn btn-info btn-sm mx-2">
                                        <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>`
                    }
                },
            ]
        });
        // add_questions
        $(document).ready(function() {
            $("#add_question_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('question.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#add_question_form")[0].reset();
                            $("#error_name_add").html(null);
                            $("#error_content_add").html(null);
                            $("#addquestionModal").modal('hide');
                            $(".content_add").summernote('reset')
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
        // show questions
        $(document).on('click', '#show_question', function(e) {
            $("#error_name_update").html(null);
            $("#error_content_update").html(null);
            $('#updatequestionModal').modal('show');
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: '{{ route('question.show') }}',
                method: 'get',
                data: {
                    id: id,
                },
                success: function(response) {
                    console.log(response.data)
                    $(".id_question").val(response.data.id);
                    $(".name_question").val(response.data.name);
                    $(".content_question").summernote('code', response.data.content);
                }
            });
        })
        $(document).on('click', '#close_update', function(e) {
            $('#updatequestionModal').modal('hide');
        })
        // update question
        $(document).ready(function() {
            $("#update_question_form").submit(function(e) {
                e.preventDefault();
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('question.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            table.ajax.reload();
                            $("#update_question_form")[0].reset();
                            $("#error_question_update").html(null);
                            $("#updatequestionModal").modal('hide');
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
        // delete_questions
        $(document).on('click', '#delete_question', function(e) {
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
                            url: '{{ route('question.delete') }}',
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
