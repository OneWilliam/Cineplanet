<div class="register-container">
    <form class="register-form" method="POST" action="/register">
        <h2>Crear Cuenta</h2>

        <div class="form-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
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
</style>