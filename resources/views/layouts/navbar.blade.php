<nav class="main-header navbar navbar-expand navbar-white navbar-light sticky-top">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Left navbar links -->
    <ul class="navbar-nav ml-auto mr-4">
        <!-- User -->
        <li class="nav-item dropdown">
            <a class="nav-link p-0" data-toggle="dropdown" href="#">
                <div class="user-panel d-flex" style="width: 100%; height:100%">
                    @if (count(auth()->user()->images))
                        @php
                            $mainImage = auth()
                                ->user()
                                ->images->firstWhere('main', 1);
                            $displayImage = $mainImage
                                ? $mainImage
                                : auth()
                                    ->user()
                                    ->images->first();
                        @endphp

                        <img src="{{ $displayImage->path }}" alt="{{ auth()->user()->name }}"
                            class="w-px-40 h-auto rounded-circle" />
                    @else
                        <img src="/admin-layout/dist/img/user2-160x160.jpg" alt class="w-px-40 h-auto rounded-circle" />
                    @endif
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item">
                    <i class="fa-solid fa-signature"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('profile.edit', [Auth::user()->id]) }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <div class="dropdown-divider"></div>
                <a id="change_password" class="dropdown-item" data-toggle="modal" style="cursor: pointer">
                    <i class="fas fa-pen mr-2"></i> Change password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>


</nav>

<!-- Modal change password-->
<div id="modal_change_password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
            </div>
            <form id="change_password_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mx-4">
                    <div class="form-group mt-3">
                        <input type="password" class="form-control" id="current_password" name="current_password"
                            placeholder="Current Password">
                        <small id="error_current_password" class="form-text text-danger"></small>
                        <small id="password_unique" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group mt-2">
                        <input type="password" class="form-control" id="new_password" name="new_password"
                            placeholder="New Password">
                        <small id="error_new_password" class="form-text text-danger"></small>
                    </div>
                    <div class="form-group mt-2">
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" placeholder="Confirmation Password">
                        <small id="error_password_confirmation" class="form-text text-danger"></small>
                    </div>
                </div>


                <div class="modal-footer">
                    <button id="close_change" type="button" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '#change_password', function(e) {
        $('#modal_change_password').modal('show');
    })
    $(document).on('click', '#close_change', function(e) {
        $('#modal_change_password').modal('hide');
    })
</script>
<script>
    // set up token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // change password
    $(document).ready(function() {
        $("#change_password_form").submit(function(e) {
            e.preventDefault();
            var fd = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '{{ route('changepassword.send') }}',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: (response) => {

                    if (response.status == true) {
                        $("#error_current_password").html(null);
                        $("#error_new_password").html(null);
                        $("#error_password_confirmation").html(null);
                        $("#password_unique").html(null);
                        $("#change_password_form")[0].reset();
                        $('#modal_change_password').modal('hide');
                        swal("Change Password Success", {
                            icon: "success",
                        })
                    } else {
                        $("#password_unique").html("Current password mismatched");
                        $("#error_current_password").html(null);
                        $("#error_new_password").html(null);
                        $("#error_password_confirmation").html(null);
                    }
                },
                error: (error) => {

                    if (error.responseJSON.errors.current_password) {
                        $("#error_current_password").html(error.responseJSON.errors
                            .current_password);
                    } else {
                        $("#error_current_password").html(null);
                    }

                    if (error.responseJSON.errors.new_password) {
                        $("#error_new_password").html(error.responseJSON.errors
                            .new_password);
                    } else {
                        $("#error_new_password").html(null);
                    }
                    if (error.responseJSON.errors.password_confirmation) {
                        $("#error_password_confirmation").html(error.responseJSON.errors
                            .password_confirmation);
                    } else {
                        $("#error_password_confirmation").html(null);
                    }

                }
            })
        })
    });
    // update profile
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navItem = document.querySelector('.nav-item a[data-widget="pushmenu"]');
        navItem.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-collapse');
        });
    });
</script>
