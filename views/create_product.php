<?php include 'views/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Nuevo Producto</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?route=create_product" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code">Código</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                        <small class="form-text text-muted">Código único del producto (ej: PROD-001)</small>
                    </div>
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Precio</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Cantidad</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="stock_minimo">Stock mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" min="0" value="1" required>
                        <small class="form-text text-muted">Cantidad mínima antes de considerar el producto en riesgo.</small>
                    </div>
                    <div class="form-group">
                        <label for="imagen">Imagen del producto (opcional)</label>
                        <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*">
                        <small class="form-text text-muted">Formatos permitidos: jpg, png, jpeg, gif.</small>
                    </div>
                    <div class="form-group">
                        <a href="index.php?route=products" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?> 