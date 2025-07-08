<?php include 'views/header.php'; ?>

<div class="jumbotron">
    <h1 class="display-4">Bienvenido al Sistema de Inventario</h1>
    <p class="lead">Un sistema completo para gestionar tu inventario de productos de manera eficiente.</p>
    <hr class="my-4">
    <?php if(!isset($_SESSION['user_id'])): ?>
        <p>Para comenzar, inicia sesión o regístrate si aún no tienes una cuenta.</p>
        <a class="btn btn-primary btn-lg mr-2" href="index.php?route=login" role="button">Iniciar Sesión</a>
        <a class="btn btn-secondary btn-lg" href="index.php?route=register" role="button">Registrarse</a>
    <?php else: ?>
        <p>¡Bienvenido <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        <a class="btn btn-primary btn-lg" href="index.php?route=products" role="button">Ver Productos</a>
    <?php endif; ?>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-box"></i> Gestión de Productos</h5>
                <p class="card-text">Administra tu inventario de productos de manera eficiente.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-line"></i> Control de Stock</h5>
                <p class="card-text">Mantén un control preciso de las cantidades disponibles.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-shield-alt"></i> Seguridad</h5>
                <p class="card-text">Sistema seguro con autenticación de usuarios.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?> 