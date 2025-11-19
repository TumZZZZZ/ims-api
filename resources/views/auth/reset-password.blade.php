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

        <!-- ===== RESET PASSWORD FORM ===== -->
        <section>
            <header class="brand">
                <div class="logo" aria-hidden="true">KA</div>
                <div>
                    <h1>Reset Password</h1>
                    <p>Enter your new password to complete the process</p>
                </div>
            </header>

            <form id="reset-form" method="POST" action="{{ route('reset.password') }}">
                @csrf
                <input name="id" type="hidden" value="{{ $id }}">

                <div class="input">
                    <label for="new-password">New Password</label>
                    <input id="new-password" name="password" type="password" placeholder="Enter new password" required>
                </div>

                <div class="input">
                    <label for="confirm-password">Confirm Password</label>
                    <input id="confirm-password" name="confirm_password" type="password" placeholder="Confirm new password" required>
                </div>

                <button class="btn" type="submit">Reset Password</button>

                <div class="actions">
                    <div class="forgot">
                        <a href="{{ route('verify.otp.form') }}">← Back to Verify OTP</a>
                    </div>
                </div>
            </form>
        </section>

    </main>

    <script>
        const resetForm = document.getElementById('reset-form');

        resetForm.addEventListener('submit', (e) => {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault(); // Only prevent if passwords don't match
                alert('Passwords do not match!');
            }
            // If passwords match, the form will submit naturally
        });
    </script>
</body>

</html>
