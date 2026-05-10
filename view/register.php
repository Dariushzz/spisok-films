<section class="auth-section">
    <h2>Registrarse</h2>
    <form action="controller/AuthController.php" method="POST" class="auth-form">
        <input type="hidden" name="action" value="register">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required minlength="6">
        </div>
        <button type="submit" class="btn-submit">Crear cuenta</button>
    </form>
    <p class="auth-link">¿Ya tienes cuenta? <a href="index.php?page=login">Inicia sesión</a></p>
</section>
