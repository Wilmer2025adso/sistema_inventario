<?php include 'views/header.php'; ?>

<?php 
$isSuperAdmin = (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'super_admin');
$empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="animate__animated animate__fadeIn">
            <i class="fas fa-tachometer-alt"></i> Dashboard
            <?php if(!$isSuperAdmin && isset($_SESSION['empresa_nombre'])): ?>
                - <?php echo htmlspecialchars($_SESSION['empresa_nombre']); ?>
            <?php endif; ?>
        </h2>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Productos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['total_productos'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Valor del Inventario
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            $<?php echo number_format($stats['valor_inventario'] ?? 0, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Clientes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($stats['total_clientes'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Ventas del Mes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            $<?php echo number_format($ventas_stats['total_ingresos'] ?? 0, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos y Estadísticas Detalladas -->
<div class="row mb-4">
    <!-- Gráfico de Ventas por Día -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4 animate__animated animate__fadeInLeft">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-area"></i> Ventas de los Últimos 7 Días
                </h6>
            </div>
            <div class="card-body">
                <canvas id="ventasChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Productos Más Vendidos -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4 animate__animated animate__fadeInRight">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-star"></i> Productos Más Vendidos
                </h6>
            </div>
            <div class="card-body">
                <?php if(!empty($productos_mas_vendidos)): ?>
                    <?php foreach($productos_mas_vendidos as $index => $producto): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge badge-primary mr-2"><?php echo $index + 1; ?></span>
                                <small class="font-weight-bold"><?php echo htmlspecialchars($producto['producto']); ?></small>
                            </div>
                            <div class="text-right">
                                <small class="text-muted"><?php echo $producto['total_vendido']; ?> unidades</small><br>
                                <small class="text-success">$<?php echo number_format($producto['total_ingresos'], 2); ?></small>
                            </div>
                        </div>
                        <?php if($index < count($productos_mas_vendidos) - 1): ?>
                            <hr class="my-2">
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No hay datos de ventas disponibles</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de Ventas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Ventas (Mes)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($ventas_stats['total_ventas'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Promedio por Venta
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            $<?php echo number_format($ventas_stats['promedio_venta'] ?? 0, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calculator fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Ventas Pendientes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($ventas_stats['ventas_pendientes'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Productos en Stock Crítico
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format(count($critical_products ?? [])); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Productos en Stock Crítico -->
<?php if(!empty($critical_products)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow animate__animated animate__fadeInUp">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-exclamation-triangle"></i> Productos en Stock Crítico
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Código</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($critical_products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['code']); ?></td>
                                <td>
                                    <span class="badge badge-danger">
                                        <?php echo $product['quantity']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">
                                        <?php echo $product['stock_minimo']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?route=edit_product&id=<?php echo $product['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Acciones Rápidas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow animate__animated animate__fadeInUp">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="index.php?route=create_product" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Nuevo Producto
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?route=create_cliente" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i> Nuevo Cliente
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?route=create_venta" class="btn btn-info btn-block">
                            <i class="fas fa-shopping-cart"></i> Nueva Venta
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?route=movimiento" class="btn btn-warning btn-block">
                            <i class="fas fa-exchange-alt"></i> Registrar Movimiento
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para el gráfico de ventas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para el gráfico de ventas
    const ventasData = <?php echo json_encode($ventas_por_dia ?? []); ?>;
    
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ventasData.map(item => item.fecha),
            datasets: [{
                label: 'Ventas ($)',
                data: ventasData.map(item => item.ingresos),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
});
</script>

<?php include 'views/footer.php'; ?> 