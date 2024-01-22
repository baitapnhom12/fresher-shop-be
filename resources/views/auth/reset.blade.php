<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>

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
                <p class="login-box-msg">Reset Password</p>

                <form action="{{ route('reset.send') }}" method="post">
                    @csrf
                    <input type="text" name="email" value="{{ $email }}" hidden>

                    <div class="input-group mb-3">
                        <input name="otp" type="text" class="form-control {{session('otp') ? 'is-invalid' :''}}" placeholder="Otp" value="{{old('otp')}}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @if (session('otp'))
                        <span class="invalid-feedback" role="alert">
                            <strong>OTP does not exist</strong>
                        </span>
                        @endif
                        
                    </div>


                    <div class="input-group mb-3">
                        <input name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password" value="{{old('password')}}" required>

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="input-group mb-3">
                        <input name="password_confirmation" type="password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            placeholder="Password Confirmation" value="{{old('password_confirmation')}}" required>

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password_confirmation')
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
</body>

</html>