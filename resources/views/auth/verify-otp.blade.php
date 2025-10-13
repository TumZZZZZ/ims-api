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

        <!-- ===== OTP FORM ===== -->
        <section id="otp-section">
            <header class="brand">
                <div class="logo" aria-hidden="true">AW</div>
                <div>
                    <h1>Verify OTP</h1>
                    <p>Enter the 6-digit code sent to your email</p>
                </div>
            </header>

            <form id="otp-form" method="POST" action="{{ route('verify.otp.code') }}">
                @csrf
                <div class="otp-inputs" style="display:flex; gap:10px; justify-content:center; margin-bottom:15px;">
                    <input name="digit_1" type="text" value="{{ old('digit_1') }}" maxlength="1" required>
                    <input name="digit_2" type="text" value="{{ old('digit_2') }}" maxlength="1" required>
                    <input name="digit_3" type="text" value="{{ old('digit_3') }}" maxlength="1" required>
                    <input name="digit_4" type="text" value="{{ old('digit_4') }}" maxlength="1" required>
                    <input name="digit_5" type="text" value="{{ old('digit_5') }}" maxlength="1" required>
                    <input name="digit_6" type="text" value="{{ old('digit_6') }}" maxlength="1" required>
                </div>
                @if (session('errors'))
                    <label for="text" style="color: #ffb55a;">{{ session('errors') }}</label>
                @endif

                <button class="btn" type="submit">Verify OTP</button>

                <div class="actions">
                    <div class="forgot"><a href={{ route('forgot.password') }}>← Back to Forgot Password</a></div>
                </div>
            </form>
        </section>

    </main>

    <script>
        const otpInputs = document.querySelectorAll('#otp-section input[type="text"]');

        otpInputs.forEach((input, index) => {

            input.addEventListener('input', (e) => {
                // If user typed one digit, move to next
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            // Handle paste event
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                // Only allow digits, max length = number of OTP inputs
                const digits = pasteData.replace(/\D/g, '').slice(0, otpInputs.length);
                digits.split('').forEach((digit, i) => {
                    otpInputs[i].value = digit;
                });
                // Focus the last input or next empty
                const firstEmpty = Array.from(otpInputs).find(i => i.value === '');
                if (firstEmpty) firstEmpty.focus();
                else otpInputs[otpInputs.length - 1].focus();
            });

            // Handle backspace or delete key
            input.addEventListener('keydown', (e) => {
                if ((e.key === 'Backspace' || e.key === 'Delete') && input.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });
    </script>
</body>

</html>
