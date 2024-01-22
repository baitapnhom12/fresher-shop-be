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
                <h5 class="">Update Page</h5>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ Route('page.update', $page->id) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-2">
                        <label for="">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $page->name) }}" placeholder="Name">
                        @error('name')
                            <small id="error_page_name" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="">Content</label>
                        <textarea id="page_editor" name="content" class="content_page form-control" rows="4">{{ old('content', $page->content) }}</textarea>
                        @error('content')
                            <small id="error_page_name" class="form-text text-danger">{{ $message }}</small>
                        @enderror
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
    </script>
@endsection
