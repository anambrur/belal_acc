<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Sign in — {{ $store?->store_name ?? 'MH POS' }}</title>

    <link rel="icon" type="image/png"
        href="{{ $store?->logo_url ?? asset('logasset/images/icons/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Syne:wght@600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
</head>

<body class="login-page">
    @php
        $storeName = $store?->store_name ?? 'MH POS';
        $logoUrl = $store?->logo_url ?? asset('assets/img/anamsLogo.png');
    @endphp

    <div class="login-shell">
        <aside class="login-visual" aria-hidden="false">
            <div class="login-visual__media" role="img" aria-label="Store workspace"></div>
            <div class="login-visual__shade"></div>

            <div class="login-visual__brand">
                <div class="login-visual__logo">
                    <img src="{{ $logoUrl }}" alt="{{ $storeName }}" />
                </div>
                <h1 class="login-visual__name">{{ $storeName }}</h1>
                <p class="login-visual__tagline">
                    Sales, purchases, and ledgers — one calm place to run the numbers.
                </p>
                <span class="login-visual__accent" aria-hidden="true"></span>
            </div>
        </aside>

        <main class="login-panel">
            <div class="login-panel__inner">
                <p class="login-panel__eyebrow">Welcome back</p>
                <h2 class="login-panel__title">Sign in to continue</h2>
                <p class="login-panel__subtitle">
                    Enter your account details to access the dashboard.
                </p>

                @if (session('status'))
                    <p class="login-status" role="status">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form" novalidate>
                    @csrf

                    <div class="login-field">
                        <label for="email">Email</label>
                        <div class="login-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 6.5h16v11H4v-11Zm0 0 8 6 8-6" />
                            </svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                placeholder="you@example.com" autocomplete="username" required autofocus />
                        </div>
                        @error('email')
                            <p class="login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="password">Password</label>
                        <div class="login-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                aria-hidden="true">
                                <rect x="5" y="10" width="14" height="10" rx="2" />
                                <path stroke-linecap="round" d="M8 10V8a4 4 0 0 1 8 0v2" />
                            </svg>
                            <input id="password" type="password" name="password" placeholder="••••••••"
                                autocomplete="current-password" required />
                            <button type="button" class="login-toggle-pass" id="togglePassword"
                                aria-label="Show password">
                                <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12Z" />
                                    <circle cx="12" cy="12" r="2.75" />
                                </svg>
                                <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3l18 18M10.6 10.6A2.75 2.75 0 0 0 13.4 13.4M9.9 5.6A10.4 10.4 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a16.7 16.7 0 0 1-3.2 3.7M6.1 6.1A16.5 16.5 0 0 0 2.5 12S6 18.5 12 18.5c1.3 0 2.5-.3 3.6-.7" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="login-row">
                        <label class="login-remember">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                            Remember me
                        </label>
                        @if (Route::has('password.request'))
                            <a class="login-forgot" href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="login-submit">Sign in</button>
                </form>

                <p class="login-footer">
                    &copy; {{ date('Y') }} {{ $storeName }}. All rights reserved.
                </p>
            </div>
        </main>
    </div>

    <script>
        (function () {
            var btn = document.getElementById('togglePassword');
            var input = document.getElementById('password');
            if (!btn || !input) return;

            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('is-visible', show);
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        })();
    </script>
</body>

</html>
