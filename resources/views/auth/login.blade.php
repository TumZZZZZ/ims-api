<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                <div class="logo" aria-hidden="true">AW</div>
                <div>
                    <h1 id="signinTitle">Khmer Angkor — Sign In</h1>
                    <p>Welcome back — sign in to continue to your dashboard</p>
                </div>
            </header>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="you@domain.com" required
                        autocomplete="email">
                </div>

                <div class="input">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password" required
                        autocomplete="current-password">
                </div>

                <div class="actions">
                    <label class="remember"><input class="custom-checkbox" type="checkbox" name="remember"> Remember me</label>
                    <div class="forgot"><a href={{ route('forgot.password') }}>Forgot password?</a></div>
                </div>

                <button class="btn" type="submit">Sign In</button>
            </form>
        </section>

        <!-- ===== FORGOT PASSWORD FORM ===== -->
        <section id="forgot-section" style="display:none;">
            <header class="brand">
                <div class="logo" aria-hidden="true">AW</div>
                <div>
                    <h1>Forgot Password</h1>
                    <p>Enter your email to receive an OTP code</p>
                </div>
            </header>

            <form id="forgot-form" action="#" method="POST" novalidate>
                <div class="input">
                    <label for="forgot-email">Email</label>
                    <input id="forgot-email" name="forgot-email" type="email" placeholder="you@domain.com" required>
                </div>

                <button class="btn" type="button" id="send-otp-btn">Send OTP</button>

                <div class="actions">
                    <div class="forgot"><a href="#" id="back-to-login">← Back to Login</a></div>
                </div>
            </form>
        </section>

        <!-- ===== OTP FORM ===== -->
        <section id="otp-section" style="display:none;">
            <header class="brand">
                <div class="logo" aria-hidden="true">AW</div>
                <div>
                    <h1>Verify OTP</h1>
                    <p>Enter the 6-digit code sent to your email</p>
                </div>
            </header>

            <form id="otp-form" action="#" method="POST" novalidate>
                <div class="otp-inputs" style="display:flex; gap:10px; justify-content:center; margin-bottom:15px;">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                </div>

                <button class="btn" type="submit">Verify OTP</button>

                <div class="actions">
                    <div class="forgot"><a href="#" id="back-to-forgot">← Back to Forgot Password</a></div>
                </div>
            </form>
        </section>

        <!-- ===== RESET PASSWORD FORM ===== -->
        <section id="reset-section" style="display:none;">
            <header class="brand">
                <div class="logo" aria-hidden="true">AW</div>
                <div>
                    <h1>Reset Password</h1>
                    <p>Enter your new password to complete the process</p>
                </div>
            </header>

            <form id="reset-form" action="#" method="POST" novalidate>
                <div class="input">
                    <label for="new-password">New Password</label>
                    <input id="new-password" name="new-password" type="password" placeholder="Enter new password" required>
                </div>

                <div class="input">
                    <label for="confirm-password">Confirm Password</label>
                    <input id="confirm-password" name="confirm-password" type="password" placeholder="Confirm new password" required>
                </div>

                <button class="btn" type="submit">Reset Password</button>

                <div class="actions">
                    <div class="forgot"><a href="#" id="back-to-otp">← Back to Verify OTP</a></div>
                </div>
            </form>
        </section>

    </main>

    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
