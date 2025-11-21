<div class="login-container">
    <form class="login-form" method="POST" action="/login">
        <h2>Iniciar Sesión</h2>

        <?php
        $error = $_GET['error'] ?? '';
        if ($error === 'invalid_credentials'): ?>
            <div class="error-message">Email o contraseña incorrectos.</div>
        <?php elseif ($error === 'missing_fields'): ?>
            <div class="error-message">Por favor, completa todos los campos.</div>
        <?php elseif ($error === 'invalid_email'): ?>
            <div class="error-message">Por favor, introduce un email válido.</div>
        <?php elseif ($error === 'system_error'): ?>
            <div class="error-message">Error del sistema. Por favor, intenta más tarde.</div>
        <?php endif; ?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="login-btn">Entrar</button>
    </form>

    <p class="register-link">¿No tienes cuenta? <a href="/register">Regístrate aquí</a></p>
</div>

<style>
    .login-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
    }

    .login-form {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .login-btn {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }

    .login-btn:hover {
        background-color: #0056b3;
    }

    .register-link {
        text-align: center;
        margin-top: 15px;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        border: 1px solid #f5c6cb;
    }
</style>