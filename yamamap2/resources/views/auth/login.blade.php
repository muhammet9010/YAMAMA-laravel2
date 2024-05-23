@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-bMgt1K5d5HlYHo5/ZzFw3q5mz5B9f5g5FV9EAgWB8zF8c5Ea6aYBdW5Ou9O5F5M5P5T5E5d5A5f5E5O5T5F5V5E5A5T5H5V5U5N5Q5R5E5L5"
    crossorigin="anonymous">

@section('content')
    <div class="container">
        <div class="authentication-wrapper authentication-cover authentication-bg">
            <div class="authentication-inner row">
                <!-- /Left Text -->
                <div class="d-none d-lg-flex col-lg-7 p-0">
                    <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                        <img src="{{ asset('assets/img/illustrations/auth-login-illustration-dark.png') }}"
                            alt="auth-login-cover" class="img-fluid my-5 auth-illustration"
                            data-app-light-img="illustrations/auth-login-illustration-light.png"
                            data-app-dark-img="illustrations/auth-login-illustration-dark.png">

                        <img src="{{ asset('assets/img/illustrations/bg-shape-image-dark.png') }}" alt="auth-login-cover"
                            class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png"
                            data-app-dark-img="illustrations/bg-shape-image-dark.png">
                    </div>
                </div>
                <!-- /Left Text -->

                <!-- Login -->
                <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        <!-- Logo -->
                        <div class="app-brand mb-4">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span style="width: 20px m-auto" class="app-brand-logo demo"><img
                                        class="
                                  w-50 m-auto "
                                        src="{{ asset('assets/img/admin/basic-file.png') }}" /></span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        {{-- <h3 class=" mb-1">مرحبا يا عزيزي ! 👋</h3> --}}
                        <p class="mb-4">يرجي تسجيل دخول لمتابعه عملك ..</p>

                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">الايميل</label>

                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="يرجا ادخال الايميل الخاص بك" required
                                    autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password"> كلمة السر </label>

                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        {{-- placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" --}} aria-describedby="password" required
                                        autocomplete="current-password" />


                                    {{-- <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off">h</i></span> --}}

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="showPassword">
                                <label class="form-check-label" for="showPassword">إظهار كلمة المرور</label>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div> --}}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100">
                                تسجيل دخول
                            </button>
                        </form>



                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons fa-brands fa-google fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                                <i class="tf-icons fa-brands fa-twitter fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var passwordInput = document.getElementById("password");
            var showPasswordCheckbox = document.getElementById("showPassword");

            showPasswordCheckbox.addEventListener("change", function() {
                if (this.checked) {
                    passwordInput.type = "text";
                } else {
                    passwordInput.type = "password";
                }
            });
        });
    </script>
@endsection
