document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById('{{ registrationForm.plainPassword.vars.id }}');
    const showPasswordCheckbox = document.getElementById('showPassword');

    showPasswordCheckbox.addEventListener('change', function () {
        passwordField.type = this.checked ? 'text' : 'password';
    });
});