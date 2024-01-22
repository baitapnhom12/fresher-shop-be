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
                <h3 class="card-title">Create New Category</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('category.store') }} " method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Category name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="exampleInputEmail1" name="name" placeholder="Enter category name"
                            value="{{ old('name') }}" required>
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
                            <option class="form-control"></option>
                            @foreach ($categories as $cate)
                                <option class="form-control" value="{{ $cate->id }}"
                                    {{ old('parentId') == $cate->id ? 'selected' : '' }}>
                                    {{ $cate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="images">Images</label>
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
                    <div class="col-md-12" id="selectedImagesSection">
                        <div class="mt-1 text-center">
                            <div class="images-preview-div d-flex align-items-center"> </div>
                        </div>
                    </div>
                </div>


                <div class="card-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
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
        const input = document.getElementById('images');
        input.addEventListener('change', (event) => {
            const files = event.target.files;
            for (const file of files) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const fileData = event.target.result;
                    localStorage.setItem(`image-${file.name}`, fileData);
                };
                reader.readAsDataURL(file);
            }
        });

        const form = document.querySelector('form');
        form.addEventListener('invalid', (event) => {
            // Clear existing preview images
            const previewDiv = document.querySelector('.images-preview-div');
            previewDiv.innerHTML = '';

            // Restore images from local storage
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('image-')) {
                    const imageData = localStorage.getItem(key);
                    const image = document.createElement('img');
                    image.src = imageData;
                    previewDiv.appendChild(image);
                }
            }
        });
    </script>
@endsection
