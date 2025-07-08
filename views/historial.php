<?php include 'views/header.php'; ?>
<div class="container">
    <h2 class="mb-4"><i class="fas fa-history"></i> Historial de Movimientos</h2>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="index.php" class="row align-items-end">
                <input type="hidden" name="route" value="historial">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label for="producto_id">Producto</label>
                        <select class="form-control" id="producto_id" name="producto_id">
                            <option value="">Todos</option>
                            <?php foreach($productos as $prod): ?>
                                <option value="<?php echo $prod['id']; ?>" <?php echo (isset($_GET['producto_id']) && $_GET['producto_id'] == $prod['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prod['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label for="tipo">Tipo</label>
                        <select class="form-control" id="tipo" name="tipo">
                            <option value="">Todos</option>
                            <option value="entrada" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'entrada') ? 'selected' : ''; ?>>Entrada</option>
                            <option value="salida" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'salida') ? 'selected' : ''; ?>>Salida</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label for="usuario_id">Usuario</label>
                        <select class="form-control" id="usuario_id" name="usuario_id">
                            <option value="">Todos</option>
                            <?php foreach($usuarios as $u): ?>
                                <option value="<?php echo $u['id']; ?>" <?php echo (isset($_GET['usuario_id']) && $_GET['usuario_id'] == $u['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($u['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Stock resultante</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($movimientos)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay movimientos registrados.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($movimientos as $mov): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($mov['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($mov['producto']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $mov['tipo'] === 'entrada' ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($mov['tipo']); ?>
                                </span>
                            </td>
                            <td><?php echo $mov['cantidad']; ?></td>
                            <td><?php echo $mov['stock_resultante']; ?></td>
                            <td><?php echo htmlspecialchars($mov['usuario']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?> 