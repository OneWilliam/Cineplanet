<div class="cuenta-container">
    <h2>Mi Cuenta</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Contenido para usuarios logueados -->
        <div class="usuario-info">
            <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario'); ?></h3>
            <p>Aquí podrás ver tu información personal, historial de compras, y configurar tus preferencias.</p>

            <div class="opciones-cuenta">
                <h4>Opciones de Cuenta</h4>
                <ul>
                    <li><a href="#">Ver historial de compras</a></li>
                    <li><a href="#">Actualizar información personal</a></li>
                    <li><a href="#">Cambiar contraseña</a></li>
                </ul>

                <form method="POST" action="/logout" style="margin-top: 20px;">
                    <button type="submit" class="btn-logout">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- Contenido para usuarios no logueados -->
        <div class="sesion-container">
            <h3>Iniciar Sesión</h3>
            <form class="login-form" method="POST" action="/login">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <p class="register-link">¿No tienes cuenta? <a href="/register">Regístrate aquí</a></p>
        </div>
    <?php endif; ?>

    <div class="acciones-cuenta">
        <a href="/peliculas" class="btn-volver">← Volver al Catálogo</a>
    </div>
</div>

<style>
    .cuenta-container {
        padding: 20px;
        max-width: 500px;
        margin: 0 auto;
    }

    .sesion-container, .usuario-info {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        margin: 20px 0;
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

    .btn-login, .btn-logout {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }

    .btn-logout {
        background-color: #dc3545;
    }

    .btn-login:hover, .btn-logout:hover {
        opacity: 0.9;
    }

    .register-link {
        text-align: center;
        margin-top: 15px;
    }

    .acciones-cuenta {
        text-align: center;
        margin-top: 20px;
    }

    .btn-volver {
        display: inline-block;
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-volver:hover {
        background-color: #545b62;
    }

    .opciones-cuenta ul {
        list-style: none;
        padding: 0;
    }

    .opciones-cuenta li {
        margin: 10px 0;
    }

    .opciones-cuenta a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #007bff;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .opciones-cuenta a:hover {
        background-color: #f8f9fa;
    }
</style>