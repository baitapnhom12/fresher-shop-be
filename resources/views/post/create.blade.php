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
                <h5 class="">Create Post</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="add_post_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-2">
                        <label for="">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Name">
                        <small id="error_post_title" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group mt-2">
                        <label for="">Content</label>
                        <textarea id="post_editor" name="content" class="content_post form-control" rows="4"></textarea>
                        <small id="error_post_content" class="form-text text-danger"></small>
                    </div>
                    <input type="text" value="{{ $enums['active'] }}" name="active" hidden>
                    <input type="text" name="popular" value="{{ $enums['unpopular'] }}" hidden>

                    <label for="">Categories</label>
                    <div class="d-flex flex-wrap" style="gap: 0 15px">
                        @foreach ($articles as $value)
                            <div class="checkbox-wrapper-14">
                                <input id="s1-14" type="checkbox" class="switch" value="{{ $value->id }}"
                                    name="articles[]">
                                <span>{{ $value->name }}</span>
                            </div>
                        @endforeach
                    </div>
                    <small id="error_post_articles" class="form-text text-danger"></small>
                    <div class="form-group">
                        <label for="images">Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="custom-file-input"
                                    placeholder="Choose image" onchange="loadFile(event)">
                                <label class="custom-file-label" for="images">Choose file</label>
                            </div>
                        </div>
                        <small id="error_post_image" class="form-text text-danger"></small>
                        <img class="mt-1" id="img_post" src="" alt="" width="150">
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
        function onlyActive(checkbox) {
            var checkboxes = document.getElementsByName('active')
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false
            })
        }

        function onlyPopular(checkbox) {
            var checkboxes = document.getElementsByName('popular')
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false
            })
        }
        $("#post_editor").summernote({
            height: 320,
        })
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //add brand
        $(document).ready(function() {
            $("#add_post_form").submit(function(e) {
                e.preventDefault();
                $('.btn_submit').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_submit').attr('disabled')
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('post.store') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        console.log(response);
                        if (response.status == true) {
                            $("#error_post_title").html(null);
                            $("#error_post_content").html(null);
                            $("#error_post_articles").html(null);
                            $("#error_post_image").html(null);
                            $('#img_post').attr('src', '')
                            $("#add_post_form")[0].reset()
                            $("#post_editor").summernote('reset')
                            $('.btn_submit').find('.fa-spinner').remove()
                            $('.btn_submit').removeAttr('disabled')
                            swal("Created Success", {
                                icon: "success",
                            })
                        }
                    },
                    error: (error) => {
                        if (error) {
                            // $("#error_brand").html(error.responseJSON.errors.name);
                            $('.btn_submit').find('.fa-spinner').remove();
                            $('.btn_submit').removeAttr('disabled');
                        }
                        if (error.responseJSON.errors.title) {
                            $("#error_post_title").html(error.responseJSON.errors.title);
                        } else {
                            $("#error_post_title").html(null);
                        }
                        if (error.responseJSON.errors.content) {
                            $("#error_post_content").html(error.responseJSON.errors.content);
                        } else {
                            $("#error_post_content").html(null);
                        }
                        if (error.responseJSON.errors.active) {
                            $("#error_post_active").html(error.responseJSON.errors.active);
                        } else {
                            $("#error_post_active").html(null);
                        }
                        if (error.responseJSON.errors.popular) {
                            $("#error_post_popular").html(error.responseJSON.errors.popular);
                        } else {
                            $("#error_post_popular").html(null);
                        }
                        if (error.responseJSON.errors.articles) {
                            $("#error_post_articles").html(error.responseJSON.errors.articles);
                        } else {
                            $("#error_post_articles").html(null);
                        }
                        if (error.responseJSON.errors.image) {
                            $("#error_post_image").html(error.responseJSON.errors.image);
                        } else {
                            $("#error_post_image").html(null);
                        }
                    }
                })
            })
        });
        var loadFile = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                $('#img_post').attr('src', reader.result)
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endsection
