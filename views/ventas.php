<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="animate__animated animate__fadeIn">
        <i class="fas fa-shopping-cart"></i> Gestión de Ventas
    </h2>
    <a href="index.php?route=create_venta" class="btn btn-primary animate__animated animate__fadeIn">
        <i class="fas fa-plus"></i> Nueva Venta
    </a>
</div>

<!-- Estadísticas de Ventas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($ventas_stats['total_ventas'] ?? 0); ?></h4>
                        <p class="mb-0">Total Ventas (Mes)</p>
                    </div>
                    <div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>$<?php echo number_format($ventas_stats['total_ingresos'] ?? 0, 2); ?></h4>
                        <p class="mb-0">Ingresos (Mes)</p>
                    </div>
                    <div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>$<?php echo number_format($ventas_stats['promedio_venta'] ?? 0, 2); ?></h4>
                        <p class="mb-0">Promedio por Venta</p>
                    </div>
                    <div>
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($ventas_stats['ventas_pendientes'] ?? 0); ?></h4>
                        <p class="mb-0">Ventas Pendientes</p>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card animate__animated animate__fadeIn">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Cliente</th>
                        <th><i class="fas fa-user-tie"></i> Vendedor</th>
                        <th><i class="fas fa-dollar-sign"></i> Total</th>
                        <th><i class="fas fa-toggle-on"></i> Estado</th>
                        <th><i class="fas fa-calendar"></i> Fecha</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($ventas)): ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> No hay ventas registradas
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($ventas as $venta): ?>
                            <tr class="animate__animated animate__fadeIn">
                                <td>
                                    <span class="badge badge-secondary">
                                        #<?php echo $venta['id']; ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($venta['cliente_nombre']); ?></strong>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($venta['usuario_nombre']); ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        $<?php echo number_format($venta['total'], 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $estado_class = '';
                                    $estado_text = '';
                                    switch($venta['estado']) {
                                        case 'completada':
                                            $estado_class = 'success';
                                            $estado_text = 'Completada';
                                            break;
                                        case 'pendiente':
                                            $estado_class = 'warning';
                                            $estado_text = 'Pendiente';
                                            break;
                                        case 'cancelada':
                                            $estado_class = 'danger';
                                            $estado_text = 'Cancelada';
                                            break;
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $estado_class; ?>">
                                        <?php echo $estado_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?route=ver_venta&id=<?php echo $venta['id']; ?>" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($venta['estado'] === 'pendiente'): ?>
                                            <a href="index.php?route=edit_venta&id=<?php echo $venta['id']; ?>" 
                                               class="btn btn-sm btn-warning" 
                                               data-bs-toggle="tooltip" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?route=delete_venta&id=<?php echo $venta['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar esta venta?')"
                                           data-bs-toggle="tooltip" 
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?> 