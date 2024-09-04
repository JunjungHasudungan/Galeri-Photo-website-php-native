<h1>Register</h1>
<form id="registerForm">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">
    <small class="error-message" id="usernameError"></small>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <small class="error-message" id="emailError"></small>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <small class="error-message" id="passwordError"></small>

    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword">
    <small class="error-message" id="confirmPasswordError"></small>

    <button type="submit">Register</button>
    <a href="/belajar-web-native/auth/login.php" id="loginLink">Already have an account?</a>
</form>
