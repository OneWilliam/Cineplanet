<?php $page_css = '/css/login.css'; ?>

<div class="login-container">
    <form class="login-form">
        <h2>Iniciar Sesión</h2>
        
        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="login-btn">Entrar</button>
    </form>
</div>