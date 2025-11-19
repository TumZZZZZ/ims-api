<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ asset('storage/default-images/favicon.png') }}">
    <title>Khmer Angkor | Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body style="background: url('{{ asset('storage/default-images/angkor-wat.jpg') }}') center/cover no-repeat fixed;">

    <div class="overlay" aria-hidden="true"></div>

    <main class="card" role="main" aria-labelledby="signinTitle">

        <!-- ===== FORGOT PASSWORD FORM ===== -->
        <section>
            <header class="brand">
                <div class="logo" aria-hidden="true">KA</div>
                <div>
                    <h1>Forgot Password</h1>
                    <p>Enter your email to receive an OTP code</p>
                </div>
            </header>

            <form method="POST" action="{{ route('send.otp') }}">
                @csrf
                <div class="input">
                    <label for="forgot-email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@domain.com" required>
                </div>
                @if (session('errors'))
                    <label for="text" style="color: #ffb55a;">{{ session('errors') }}</label>
                @endif

                <button class="btn" type="submit">Send OTP</button>

                <div class="actions">
                    <div class="forgot"><a href={{ route('login') }}>← Back to Login</a></div>
                </div>
            </form>
        </section>

    </main>
</body>

</html>
