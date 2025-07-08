<?php include 'views/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?route=login">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </form>
                <hr>
                <p class="mb-0">¿No tienes una cuenta? <a href="index.php?route=register">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?> 