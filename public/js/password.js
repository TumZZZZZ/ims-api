// Password strength checker and auto-fill confirmation
const password = document.getElementById("password");
const confirmPassword = document.getElementById("password_confirmation");
const strengthMsg = document.getElementById("strength_msg");

password.addEventListener("input", () => {
    const pass = password.value;

    // Auto-fill confirm password
    confirmPassword.value = pass;

    // Password strength check
    let strength = "Weak";
    let color = "red";

    const hasLetter = /[A-Za-z]/.test(pass);
    const hasNumber = /[0-9]/.test(pass);
    const hasSpecial = /[^A-Za-z0-9]/.test(pass);

    if (pass.length >= 8 && (hasLetter && hasNumber)) {
        strength = "Medium";
        color = "orange";
    }
    if (pass.length >= 12 && hasLetter && hasNumber && hasSpecial) {
        strength = "Strong";
        color = "green";
    }

    if (pass.length === 0) {
        strengthMsg.innerText = "";
        return;
    }

    strengthMsg.innerText = "Password Strength: " + strength;
    strengthMsg.style.color = color;
});
