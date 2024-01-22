@extends('layouts.app')
@section('content')
    @if (session('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success("{{ session('success') }}", 'Success!', {
                timeOut: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.error("{{ session('error') }}", 'Error!', {
                timeOut: 3000
            });
        </script>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update category</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('category.update', $category->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Category name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="exampleInputEmail1" name="name" value="{{ $category->name }}"
                            placeholder="Enter category name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Parent Category</label>
                        <select name="parentId" class="form-control custom-select select2bs4"
                            @error('parentId') is-invalid @enderror" style="width: 100%;">
                            <option selected="selected" value="{{ $category->parent_id }}">
                                {{ $category->parent->name ?? '' }}
                            </option>
                            @foreach ($categories as $cate)
                                <option class="form-control" value="{{ $cate->id }}" checked>
                                    {{ $cate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>IsMain</th>
                                    <th>Style</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category->images as $image)
                                    <tr>
                                        <td><img src="{{ $image->path }}" alt="{{ $category->name }}" width="50px"
                                                height="50px"></td>
                                        <td><input class="form-check-input ml-2" type="radio" name="radio1"
                                                @if ($image->main) @checked(true) @endif
                                                data-image-id="{{ $image->id }}">
                                        </td>

                                        <td class="text-center" data-image-id="{{ $image->id }}">
                                            @if (!$image->style)
                                                <select class="select2bs4 select-style-image">
                                                    <option></option>
                                                    <option value="1">Square</option>
                                                    <option value="2">Vertical</option>
                                                    <option value="3">Horizontal</option>
                                                </select>
                                            @else
                                                <select class="select2bs4 select-style-image">
                                                    <option value="1" {{ $image->style == 1 ? 'selected' : '' }}>
                                                        Square</option>
                                                    <option value="2" {{ $image->style == 2 ? 'selected' : '' }}>
                                                        Vertical</option>
                                                    <option value="3" {{ $image->style == 3 ? 'selected' : '' }}>
                                                        Horizontal</option>
                                                </select>
                                            @endif
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
                                    placeholder="Choose images" multiple>
                                <label class="custom-file-label" for="images">Choose file</label>
                            </div>
                        </div>
                    </div>
                    @error('images')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
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

                    <div class="form-group">
                        <input type="hidden" name="imageUpdateStyle">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>

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

            $(document).ready(function() {
                $('.select-style-image').change(function() {
                    // Gather selected image data and style
                    const imageId = $(this).closest('td').data('image-id');
                    const selectedStyle = $(this).val();

                    // Build the array to push
                    const imageData = [imageId, selectedStyle];

                    // Access the hidden input element
                    const hiddenInput = $('input[name="imageUpdateStyle"]');

                    // Get the existing array (if any) from the input
                    let existingData = hiddenInput.val();
                    if (existingData) {
                        existingData = JSON.parse(existingData);
                    } else {
                        existingData = [];
                    }

                    // Check if the image data already exists in the array
                    let existingImageData = existingData.find(data => data[0] === imageId);

                    // If the image data does not exist, push it to the array
                    if (!existingImageData) {
                        existingData.push(imageData);
                    } else {
                        // Otherwise, update the image style
                        existingImageData[1] = selectedStyle;
                    }

                    // Update the hidden input with the updated array
                    hiddenInput.val(JSON.stringify(existingData));
                });
            });
        });
    </script>
@endsection
