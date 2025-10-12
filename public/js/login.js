const loginSection = document.getElementById('login-section');
const forgotSection = document.getElementById('forgot-section');
const otpSection = document.getElementById('otp-section');
const resetSection = document.getElementById('reset-section');

document.getElementById('forgot-link').onclick = (e) => {
    e.preventDefault();
    loginSection.style.display = 'none';
    forgotSection.style.display = 'block';
};

document.getElementById('back-to-login').onclick = (e) => {
    e.preventDefault();
    forgotSection.style.display = 'none';
    loginSection.style.display = 'block';
};

document.getElementById('send-otp-btn').onclick = (e) => {
    e.preventDefault();
    forgotSection.style.display = 'none';
    otpSection.style.display = 'block';
};

document.getElementById('back-to-forgot').onclick = (e) => {
    e.preventDefault();
    otpSection.style.display = 'none';
    forgotSection.style.display = 'block';
};

document.getElementById('back-to-otp').onclick = (e) => {
    e.preventDefault();
    resetSection.style.display = 'none';
    otpSection.style.display = 'block';
};

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
});

// ===== ACTIONS EACH FORM =====
const otpForm = document.getElementById('otp-form');

// After OTP form submit (simulate OTP verification success)
otpForm.addEventListener('submit', (e) => {
    e.preventDefault();
    // Hide OTP section
    otpSection.style.display = 'none';
    // Show Reset Password section
    resetSection.style.display = 'block';
});

// Optional: Validate password match
const resetForm = document.getElementById('reset-form');
resetForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }
    alert('Password reset successful ✅');
    // Optionally, redirect to login page
    resetSection.style.display = 'none';
    loginSection.style.display = 'block';
});
