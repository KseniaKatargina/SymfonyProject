function togglePasswordVisibility(passwordFieldId) {
    const passwordField = document.getElementById(passwordFieldId);
    const currentType = passwordField.type;

    // Изменение типа поля ввода с "password" на "text" и обратно
    passwordField.type = (currentType === 'password') ? 'text' : 'password';
}