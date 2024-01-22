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
        <div class="card">
            <div class="card-header">
                <h5 class="">Create Banner</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="add_banner_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-2">
                        <label for="exampleInputEmail1">Name Banner</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="name"
                            placeholder="Name">
                        <small id="error_banner" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="images">Images</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="images" id="images" class="custom-file-input"
                                    placeholder="Choose images" onchange="loadFile(event)">
                                <label class="custom-file-label" for="images">Choose file</label>
                            </div>
                        </div>
                        <small id="error_banner_image" class="form-text text-danger"></small>
                        <img class="mt-1" id="img_banner" src="" alt="" width="150">
                       
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
        // set up token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //add banner
        $(document).ready(function() {
            $("#add_banner_form").submit(function(e) {
                e.preventDefault();
                $('.btn_submit').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_submit').attr('disabled')
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('banner.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            $('#img_banner').attr('src','')
                            $("#add_banner_form")[0].reset();
                            $("#error_banner").html(null);
                            $("#error_banner_image").html(null);
                            $('.btn_submit').find('.fa-spinner').remove();
                            $('.btn_submit').removeAttr('disabled');
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
                            $("#error_banner").html(error.responseJSON.errors.name);
                        } else {
                            $("#error_banner").html(null);
                        }
                        if (error.responseJSON.errors.images) {
                            $("#error_banner_image").html(error.responseJSON.errors.images);
                        } else {
                            $("#error_banner_image").html(null);
                        }
                    }
                })
            })
        });
        var loadFile = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                $('#img_banner').attr('src',reader.result)
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endsection
