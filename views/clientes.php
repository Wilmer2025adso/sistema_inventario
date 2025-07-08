<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="animate__animated animate__fadeIn">
        <i class="fas fa-users"></i> Gestión de Clientes
    </h2>
    <a href="index.php?route=create_cliente" class="btn btn-primary animate__animated animate__fadeIn">
        <i class="fas fa-plus"></i> Nuevo Cliente
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4 animate__animated animate__fadeIn">
    <div class="card-body">
        <form method="GET" action="index.php" class="row align-items-end">
            <input type="hidden" name="route" value="clientes">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label for="search"><i class="fas fa-search"></i> Buscar Clientes</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Buscar por nombre, email o documento..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label for="tipo"><i class="fas fa-tags"></i> Tipo</label>
                    <select class="form-control" id="tipo" name="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="individual" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'individual') ? 'selected' : ''; ?>>Individual</option>
                        <option value="empresa" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'empresa') ? 'selected' : ''; ?>>Empresa</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas de Clientes -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($clientes_stats['total_clientes'] ?? 0); ?></h4>
                        <p class="mb-0">Total Clientes</p>
                    </div>
                    <div>
                        <i class="fas fa-users fa-2x"></i>
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
                        <h4><?php echo number_format($clientes_stats['clientes_activos'] ?? 0); ?></h4>
                        <p class="mb-0">Clientes Activos</p>
                    </div>
                    <div>
                        <i class="fas fa-user-check fa-2x"></i>
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
                        <h4><?php echo number_format($clientes_stats['clientes_individuales'] ?? 0); ?></h4>
                        <p class="mb-0">Individuales</p>
                    </div>
                    <div>
                        <i class="fas fa-user fa-2x"></i>
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
                        <h4><?php echo number_format($clientes_stats['clientes_empresas'] ?? 0); ?></h4>
                        <p class="mb-0">Empresas</p>
                    </div>
                    <div>
                        <i class="fas fa-building fa-2x"></i>
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
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-phone"></i> Teléfono</th>
                        <th><i class="fas fa-tags"></i> Tipo</th>
                        <th><i class="fas fa-id-card"></i> Documento</th>
                        <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                        <th><i class="fas fa-calendar"></i> Fecha de Registro</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($clientes)): ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> No hay clientes registrados
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($clientes as $cliente): ?>
                            <tr class="animate__animated animate__fadeIn">
                                <td><?php echo $cliente['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($cliente['nombre']); ?></strong>
                                </td>
                                <td>
                                    <?php if(!empty($cliente['email'])): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($cliente['email']); ?>">
                                            <?php echo htmlspecialchars($cliente['email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($cliente['telefono'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($cliente['telefono']); ?>">
                                            <?php echo htmlspecialchars($cliente['telefono']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $cliente['tipo'] === 'individual' ? 'info' : 'warning'; ?>">
                                        <?php echo ucfirst($cliente['tipo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if(!empty($cliente['documento'])): ?>
                                        <span class="badge badge-secondary">
                                            <?php echo htmlspecialchars($cliente['documento']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($cliente['direccion'])): ?>
                                        <small><?php echo htmlspecialchars($cliente['direccion']); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">No especificada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($cliente['created'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?route=edit_cliente&id=<?php echo $cliente['id']; ?>" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?route=delete_cliente&id=<?php echo $cliente['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar este cliente?')"
                                           data-bs-toggle="tooltip" 
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="index.php?route=ver_cliente&id=<?php echo $cliente['id']; ?>" 
                                           class="btn btn-sm btn-success"
                                           data-bs-toggle="tooltip" 
                                           title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
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