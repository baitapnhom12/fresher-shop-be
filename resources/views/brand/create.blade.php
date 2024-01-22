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
                <h5 class="">Create Brand</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="add_brand_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-2">
                        <label for="exampleInputEmail1">Name Brand</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="name"
                            placeholder="Name">
                        <small id="error_brand_name" class="form-text text-danger"></small>
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
                        <small id="error_brand_image" class="form-text text-danger"></small>
                        <img class="mt-1" id="img_brand" src="" alt="" width="150">
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
        //add brand
        $(document).ready(function() {
            $("#add_brand_form").submit(function(e) {
                e.preventDefault();
                $('.btn_submit').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_submit').attr('disabled')
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('brand.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            var img_brand = document.getElementById('img_brand');
                            img_brand.src = '';
                            $("#add_brand_form")[0].reset();
                            $("#error_brand_name").html(null);
                            $("#error_brand_image").html(null);
                            $('.btn_submit').find('.fa-spinner').remove();
                            $('.btn_submit').removeAttr('disabled');
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            $("#error_brand").html(error.responseJSON.errors.name);
                            $('.btn_submit').find('.fa-spinner').remove();
                            $('.btn_submit').removeAttr('disabled');
                        }
                        if (error.responseJSON.errors.name) {
                            $("#error_brand_name").html(error.responseJSON.errors.name);
                        } else {
                            $("#error_brand_name").html(null);
                        }
                        if (error.responseJSON.errors.images) {
                            $("#error_brand_image").html(error.responseJSON.errors.images);
                        } else {
                            $("#error_brand_image").html(null);
                        }
                    }
                })
            })
        });
        var loadFile = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var img_brand = document.getElementById('img_brand');
                img_brand.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endsection
