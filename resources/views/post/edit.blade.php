@extends('layouts.app')
@section('content')
    @if (session('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success('Success', {
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
            toastr.error('Error', {
                timeOut: 3000
            });
        </script>
    @endif
    <div class="col-12">
        <div class="card card-white">
            <div class="card-header">
                <h5 class="">Update Post</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ Route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-2">
                        <label for="">Title Post</label>
                        <input value="{{ old('title', $post->title) }}" type="text" class="form-control" id=""
                            name="title" placeholder="Name">
                        @error('title')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="">Content</label>
                        <textarea id="post_editor" name="content" class="content_post form-control" rows="4">{{ old('content', $post->content) }}</textarea>
                    </div>
                    @error('content')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                  
                    <div class="form-group">
                        <label for="">Status</label>
                        <div class="d-flex flex-wrap" style="gap: 0 15px">
                            @if ($post->active == $enumactive)
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['active'] }}"
                                        name="active" onclick="onlyActive(this)" {{ old('active', 'checked') }}>
                                    <span>Active</span>
                                </div>
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['hidden'] }}"
                                        name="active" onclick="onlyActive(this)">
                                    <span>Hidden</span>
                                </div>
                            @else
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['active'] }}"
                                        name="active" onclick="onlyActive(this)">
                                    <span>Active</span>
                                </div>
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['hidden'] }}"
                                        name="active" onclick="onlyActive(this)" {{old('active') ? '' :'checked'}}>
                                    <span>Hidden</span>
                                </div>
                            @endif

                        </div>

                    </div>
                    @error('active')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    <div class="form-group">
                        <label for="">Level</label>
                        <div class="d-flex flex-wrap" style="gap: 0 15px">
                            @if ($post->popular == $enumpopular)
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['popular'] }}"
                                        name="popular" onclick="onlyPopular(this)" checked>
                                    <span>Popular</span>
                                </div>
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['unpopular'] }}"
                                        name="popular" onclick="onlyPopular(this)">
                                    <span>Unpopular</span>
                                </div>
                            @else
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['popular'] }}"
                                        name="popular" onclick="onlyPopular(this)">
                                    <span>Popular</span>
                                </div>
                                <div class="checkbox-wrapper-14">
                                    <input id="s1-14" type="checkbox" class="switch" value="{{ $enums['unpopular'] }}"
                                        name="popular" onclick="onlyPopular(this)" checked>
                                    <span>Unpopular</span>
                                </div>
                            @endif

                        </div>

                    </div>
                    @error('popular')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                    <label for="">Categories</label>
                    <div class="d-flex flex-wrap" style="gap: 0 15px">
                        @foreach ($articles as $value)
                            <div class="checkbox-wrapper-14">
                                <input {{ $articlepost->contains($value->id) ? 'checked' : '' }} id="s1-14"
                                    type="checkbox" class="switch" value="{{ $value->id }}" name="articles[]">
                                <span>{{ $value->name }}</span>
                            </div>
                        @endforeach
                    </div>
                    @error('articles')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                    <div class="form-group">
                        <label for="images">Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="custom-file-input"
                                    placeholder="Choose image" onchange="loadFile(event)">
                                <label class="custom-file-label" for="images">Choose file</label>
                            </div>
                        </div>
                        @error('image')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                        <img class="mt-1" id="img_post" src="{{$post->image}}" alt="" width="150">
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
        var loadFile = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                $('#img_post').attr('src', reader.result)
            };
            reader.readAsDataURL(event.target.files[0]);
        };

       
    </script>
@endsection
