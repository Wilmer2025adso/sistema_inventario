<?php include 'views/header.php'; ?>
<div class="container">
    <h2 class="mb-4"><i class="fas fa-exchange-alt"></i> Registrar Movimiento de Inventario</h2>
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="index.php?route=movimiento">
                <div class="form-group">
                    <label for="producto_id">Producto</label>
                    <select class="form-control" id="producto_id" name="producto_id" required>
                        <option value="">Seleccione un producto</option>
                        <?php foreach($productos as $prod): ?>
                            <option value="<?php echo $prod['id']; ?>">
                                <?php echo htmlspecialchars($prod['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de movimiento</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
            </form>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?> 