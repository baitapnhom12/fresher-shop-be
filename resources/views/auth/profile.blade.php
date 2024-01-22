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
                <h3 class="card-title">Update Profile</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="update_profile_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="text" name="id" id="id" value="{{ $profile->id }}" hidden>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $profile->name }}"
                            placeholder="Name">
                        <small id="error_name" class="form-text text-danger"></small>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ $profile->email }}"
                            placeholder="Email">
                        <small id="error_email" class="form-text text-danger"></small>
                    </div>


                    <div class="form-group">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>IsMain</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profile->images->reverse() as $image)
                                    <tr>
                                        <td><img src="{{ $image->path }}" alt="{{ $profile->name }}" width="50px"
                                                height="50px"></td>
                                        <td><input class="form-check-input ml-2" type="radio" name="radio1"
                                                @if ($image->main) @checked(true) @endif
                                                data-image-id="{{ $image->id }}">
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-sm image-delete"
                                                data-image-id="{{ $image->id }}" href="#">
                                                <i class="fas fa-trash">
                                                </i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <label for="images">Add Images</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="images[]" id="images" class="custom-file-input"
                                    placeholder="Choose images">
                                <label class="custom-file-label" for="images">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-1 text-center">
                            <div class="images-preview-div d-flex align-items-center"> </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="imageDelete">
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="imageUpdate">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" id="submit" class="btn btn-primary btn_profile">Submit</button>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#update_profile_form").submit(function(e) {
                e.preventDefault();
                $('.btn_profile').prepend('<i class="fas fa-spinner fa-spin mr-2"></i>')
                $('.btn_profile').attr('disabled')
                var fd = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('profile.update') }}',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        $('.btn_profile').find('.fa-spinner').remove();
                        $('.btn_profile').removeAttr('disabled');
                        toastr.options = {
                            "progressBar": true,
                            "closeButton": true,
                        }
                        toastr.success('Success', {
                            timeOut: 3000
                        });
                        window.location.reload();
                    },
                    error: (error) => {
                        if (error) {
                            $('.btn_profile').find('.fa-spinner').remove();
                            $('.btn_profile').removeAttr('disabled');
                        }
                        if (error.responseJSON.errors.name) {
                            $("#error_name").html(error.responseJSON.errors.name);
                        } else {
                            $("#error_name").html(null);
                        }
                        if (error.responseJSON.errors.email) {
                            $("#error_email").html(error.responseJSON.errors.email);
                        } else {
                            $("#error_email").html(null);
                        }
                        console.log(error);

                    }
                })
            })
        });
    </script>

    <script>
        $(function() {
            // Multiple images preview with JavaScript
            var previewImages = function(input, imgPreviewPlaceholder) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            $($.parseHTML('<img class="pr-3">')).attr('src', event.target.result).attr(
                                'style',
                                'max-width: 150px; height: auto;').appendTo(
                                imgPreviewPlaceholder).on('click', function() {
                                $(this).remove();
                            });
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };

            $('#images').on('change', function() {
                $('.images-preview-div').empty(); // Clear preview images before adding new ones
                previewImages(this, 'div.images-preview-div');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.image-delete');
            const radioButtons = document.querySelectorAll('input[name="radio1"]');

            let selectedImages = [];

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const imageId = this.getAttribute('data-image-id');
                    const imageRow = this.closest('tr').querySelector('img');

                    if (imageRow) {
                        if (!selectedImages.includes(imageId)) {
                            imageRow.style.opacity = '0.2';
                            selectedImages.push(imageId);
                        } else {
                            imageRow.style.opacity = '1';
                            const index = selectedImages.indexOf(imageId);
                            if (index > -1) {
                                selectedImages.splice(index, 1);
                            }
                        }

                        // Update value của input[name="imageDelete"] thành mảng các ID đã chọn
                        const deleteInput = document.querySelector('input[name="imageDelete"]');
                        if (deleteInput) {
                            deleteInput.value = JSON.stringify(selectedImages);
                        }
                    }
                });
            });

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const imageId = this.getAttribute('data-image-id');
                    // Thêm vào input[type=text] với tên là imageUpdate
                    const updateInput = document.querySelector('input[name="imageUpdate"]');
                    if (updateInput) {
                        updateInput.value = imageId;
                    }
                });
            });
        });
    </script>
@endsection
