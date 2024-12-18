 
        // Get references to password field and eye icon
        const togglePassword = document.getElementById('eyeIcon');
        const passwordField = document.getElementById('password');

        // Add click event to the eye icon
        togglePassword.addEventListener('click', function () {
            // Toggle the password field type between password and text
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon (fa-eye to fa-eye-slash and vice versa)
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    