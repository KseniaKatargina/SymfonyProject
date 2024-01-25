document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById('{{ form.plainPassword.vars.id }}');
    const showPasswordCheckbox = document.getElementById('showPassword');

    showPasswordCheckbox.addEventListener('change', function () {
        passwordField.type = this.checked ? 'text' : 'password';
    });
});