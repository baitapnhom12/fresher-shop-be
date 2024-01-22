<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forget Password</title>

    @include('layouts.style')
    @stack('style')
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Admin</b>HTV</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Forget Password</p>

                <form action="{{ route('forgot.send') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email" required value="{{old('email')}}">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                   
                    <div class="row">              
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Send</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    @include('layouts.script')
    @stack('script')
</body>

</html>