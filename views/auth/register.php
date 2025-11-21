<div class="register-container">
    <form class="register-form" method="POST" action="/register">
        <h2>Crear Cuenta</h2>

        <?php
        $error = $_GET['error'] ?? '';
        if ($error === 'email_exists'): ?>
            <div class="error-message">Este email ya está registrado.</div>
        <?php elseif ($error === 'missing_fields'): ?>
            <div class="error-message">Por favor, completa todos los campos.</div>
        <?php elseif ($error === 'invalid_email'): ?>
            <div class="error-message">Por favor, introduce un email válido.</div>
        <?php elseif ($error === 'weak_password'): ?>
            <div class="error-message">La contraseña debe tener al menos 8 caracteres.</div>
        <?php elseif ($error === 'password_mismatch'): ?>
            <div class="error-message">Las contraseñas no coinciden.</div>
        <?php elseif ($error === 'registration_failed'): ?>
            <div class="error-message">Error al crear la cuenta. Por favor, intenta más tarde.</div>
        <?php endif; ?>

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña (mínimo 8 caracteres)</label>
            <input type="password" id="password" name="password" required minlength="8">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        </div>

        <button type="submit" class="register-btn">Registrarse</button>
    </form>
    
    <p class="login-link">¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a></p>
</div>

<style>
    .register-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .register-form {
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
    
    .register-btn {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }
    
    .register-btn:hover {
        background-color: #218838;
    }
    
    .login-link {
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