<section class="auth-section">
    <h2>Iniciar Sesión</h2>
    <form action="controller/AuthController.php" method="POST" class="auth-form">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn-submit">Entrar</button>
    </form>
    <p class="auth-link">¿No tienes cuenta? <a href="index.php?page=register">Regístrate aquí</a></p>
</section>
