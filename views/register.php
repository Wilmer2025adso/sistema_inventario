<?php include 'views/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Registro de Usuario</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?route=register">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <small class="form-text text-muted">La contraseña debe tener al menos 6 caracteres.</small>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="vendedor">Vendedor</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
                <hr>
                <p class="mb-0">¿Ya tienes una cuenta? <a href="index.php?route=login">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?> 