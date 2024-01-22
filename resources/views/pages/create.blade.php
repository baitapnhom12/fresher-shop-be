@extends('layouts.app')
@section('content')
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
        <div class="card card-white">
            <div class="card-header">
                <h5 class="">Create Page</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="add_page_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-2">
                        <label for="">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        <small id="error_page_name" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group mt-2">
                        <label for="">Content</label>
                        <textarea id="page_editor" name="content" class="content_page form-control" rows="4"></textarea>
                        <small id="error_page_content" class="form-text text-danger"></small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" id="submit" class="btn btn-primary btn_submit">
                        Submit</button>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <script>
        $("#page_editor").summernote({
            height: 320,
        })
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //add brand
        $(document).ready(function() {
            $("#add_page_form").submit(function(e) {
                e.preventDefault();
                $('.btn_submit').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_submit').attr('disabled')
                var fd = new FormData(this);
                $.ajax({
                    type: 'post',
                    url: '{{ route('page.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            $("#error_page_name").html(null);
                            $("#error_page_content").html(null);
                            $("#add_page_form")[0].reset()
                            $("#page_editor").summernote('reset')
                            $('.btn_submit').find('.fa-spinner').remove()
                            $('.btn_submit').removeAttr('disabled')
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            $('.btn_submit').find('.fa-spinner').remove();
                            $('.btn_submit').removeAttr('disabled');
                        }
                        if (error.responseJSON.errors.name) {
                            $("#error_page_name").html(error.responseJSON.errors.name);
                        }
                        else{
                            $("#error_page_name").html(null);
                        }
                        if (error.responseJSON.errors.content) {
                            $("#error_page_content").html(error.responseJSON.errors.content);
                        }
                        else{
                            $("#error_page_content").html(null);
                        }                      
                    }
                })
            })
        });
    </script>
@endsection
