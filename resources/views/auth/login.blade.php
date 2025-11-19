<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ asset('storage/default-images/favicon.png') }}">
    <title>Khmer Angkor | Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body style="background: url('{{ asset('storage/default-images/angkor-wat.jpg') }}') center/cover no-repeat fixed;">

    <div class="overlay" aria-hidden="true"></div>

    <main class="card" role="main" aria-labelledby="signinTitle">

        <!-- ===== LOGIN FORM ===== -->
        <section id="login-section">
            <header class="brand">
                <div class="logo" aria-hidden="true">KA</div>
                <div>
                    <h1 id="signinTitle">Khmer Angkor — Sign In</h1>
                    <p>Welcome back — sign in to continue to your dashboard</p>
                </div>
            </header>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="you@domain.com" required value="{{ old('email') }}"
                        autocomplete="email">
                </div>

                <div class="input">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password" required value="{{ old('password') }}"
                        autocomplete="current-password">
                </div>
                <label class="password-visibility" for="password-visibility">Show Password<input class="custom-checkbox" type="checkbox" name="password-visibility"></label>

                @if ($errors->any())
                    <div style="color:red;">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <div class="actions">
                    <label class="remember" for="remember-me"><input class="custom-checkbox" type="checkbox" name="remember"> Remember me</label>
                    <div class="forgot"><a href={{ route('forgot.password') }}>Forgot password?</a></div>
                </div>

                <button class="btn" type="submit">Sign In</button>
            </form>
        </section>

    </main>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementsByName('password-visibility')[0]; // get the first checkbox with that name

        passwordToggle.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>

</body>

</html>
