document.getElementById("registerForm").addEventListener("submit", function(e) {

    let password = document.querySelector("input[name='password']").value;
    let confirm = document.querySelector("input[name='confirm_password']").value;

    if(password.length < 6) {
        alert("Password must be at least 6 characters");
        e.preventDefault();
    }

    if(password !== confirm) {
        alert("Passwords do not match");
        e.preventDefault();
    }
});