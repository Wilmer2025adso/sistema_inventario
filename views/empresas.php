<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="animate__animated animate__fadeIn">
        <i class="fas fa-building"></i> Gestión de Empresas
    </h2>
    <a href="index.php?route=create_empresa" class="btn btn-primary animate__animated animate__fadeIn">
        <i class="fas fa-plus"></i> Nueva Empresa
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4 animate__animated animate__fadeIn">
    <div class="card-body">
        <form method="GET" action="index.php" class="row align-items-end">
            <input type="hidden" name="route" value="empresas">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label for="search"><i class="fas fa-search"></i> Buscar Empresas</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Buscar por nombre, RUC o email..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label for="estado"><i class="fas fa-toggle-on"></i> Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="activo" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
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

<!-- Estadísticas de Empresas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($total_empresas ?? 0); ?></h4>
                        <p class="mb-0">Total Empresas</p>
                    </div>
                    <div>
                        <i class="fas fa-building fa-2x"></i>
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
                        <h4><?php echo number_format($empresas_activas ?? 0); ?></h4>
                        <p class="mb-0">Empresas Activas</p>
                    </div>
                    <div>
                        <i class="fas fa-check-circle fa-2x"></i>
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
                        <h4><?php echo number_format($empresas_inactivas ?? 0); ?></h4>
                        <p class="mb-0">Empresas Inactivas</p>
                    </div>
                    <div>
                        <i class="fas fa-pause-circle fa-2x"></i>
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
                        <h4><?php echo number_format($total_usuarios ?? 0); ?></h4>
                        <p class="mb-0">Total Usuarios</p>
                    </div>
                    <div>
                        <i class="fas fa-users fa-2x"></i>
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
                        <th><i class="fas fa-building"></i> Empresa</th>
                        <th><i class="fas fa-id-card"></i> RUC</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-phone"></i> Teléfono</th>
                        <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                        <th><i class="fas fa-toggle-on"></i> Estado</th>
                        <th><i class="fas fa-calendar"></i> Fecha de Registro</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($empresas)): ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> No hay empresas registradas
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($empresas as $empresa): ?>
                            <tr class="animate__animated animate__fadeIn">
                                <td><?php echo $empresa['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if(!empty($empresa['logo'])): ?>
                                            <img src="<?php echo htmlspecialchars($empresa['logo']); ?>" 
                                                 alt="Logo" class="mr-2" style="width:32px; height:32px; object-fit:cover; border-radius:4px;">
                                        <?php endif; ?>
                                        <strong><?php echo htmlspecialchars($empresa['nombre']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo htmlspecialchars($empresa['ruc']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if(!empty($empresa['email'])): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($empresa['email']); ?>">
                                            <?php echo htmlspecialchars($empresa['email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($empresa['telefono'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($empresa['telefono']); ?>">
                                            <?php echo htmlspecialchars($empresa['telefono']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No especificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($empresa['direccion'])): ?>
                                        <small><?php echo htmlspecialchars($empresa['direccion']); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">No especificada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $empresa['estado'] === 'activo' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($empresa['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($empresa['created'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?route=edit_empresa&id=<?php echo $empresa['id']; ?>" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?route=ver_empresa&id=<?php echo $empresa['id']; ?>" 
                                           class="btn btn-sm btn-success"
                                           data-bs-toggle="tooltip" 
                                           title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?route=empresa_stats&id=<?php echo $empresa['id']; ?>" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip" 
                                           title="Ver Estadísticas">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>
                                        <?php if($empresa['estado'] === 'activo'): ?>
                                            <a href="index.php?route=toggle_empresa&id=<?php echo $empresa['id']; ?>&action=deactivate" 
                                               class="btn btn-sm btn-warning"
                                               onclick="return confirm('¿Estás seguro de desactivar esta empresa?')"
                                               data-bs-toggle="tooltip" 
                                               title="Desactivar">
                                                <i class="fas fa-pause"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?route=toggle_empresa&id=<?php echo $empresa['id']; ?>&action=activate" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('¿Estás seguro de activar esta empresa?')"
                                               data-bs-toggle="tooltip" 
                                               title="Activar">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?route=delete_empresa&id=<?php echo $empresa['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar esta empresa? Esta acción no se puede deshacer.')"
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