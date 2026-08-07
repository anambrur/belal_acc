
<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $store?->store_name ?? 'MH POS' }}</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('logasset/images/icons/favicon.ico') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/vendor/bootstrap/css/bootstrap.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('logasset/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('logasset/fonts/iconic/css/material-design-iconic-font.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/vendor/animate/animate.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/vendor/css-hamburgers/hamburgers.min.css') }}" />
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animsition/css/animsition.min.css') }}" /> --}}
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/vendor/select2/select2.min.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/vendor/daterangepicker/daterangepicker.css') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/css/util.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('logasset/css/main.css') }}" />
    <!--===============================================================================================-->
</head>

<body>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('/logasset/images/imgpsh_fullsize_anim.png')">
            <div class="wrap-login100">
                {{-- <form class="login100-form validate-form"> --}}
                <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                    @csrf
                    <span class="login100-form-logo">
                        <img src="{{ $store?->logo_url ?? asset('assets/img/anamsLogo.png') }}"
                            alt="{{ $store?->store_name ?? 'logo' }}" width="130">
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27"> Log in </span>

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="email" name="email" :value="old('email')"
                            placeholder="Username" />
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>
                    <div id="error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="password" placeholder="Password" />
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>
                    <div class="text-danger">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>

                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" />
                        <label class="label-checkbox100" for="ckb1"> Remember me </label>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">Login</button>
                    </div>

                    <div class="text-center p-t-90">
                        <a class="txt1" href="{{ route('password.request') }}"> Forgot Password? </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <!--===============================================================================================-->
    <script src="{{ asset('logasset/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('logasset/vendor/animsition/js/animsition.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('logasset/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('logasset/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('logasset/vendor/select2/select2.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('logasset/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('logasset/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('logasset/vendor/countdowntime/countdowntime.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('logasset/js/main.js') }}"></script>
</body>

</html>
