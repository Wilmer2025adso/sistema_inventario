<?php include 'views/header.php'; ?>

<?php $isAdmin = (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'); ?>

<?php if ($isAdmin && !empty($critical_products)): ?>
    <div class="alert alert-danger animate__animated animate__flash" role="alert">
        <strong><i class="fas fa-exclamation-triangle"></i> ¡Atención!</strong> Hay productos en o por debajo del stock mínimo.
    </div>
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-exclamation-triangle"></i> Productos en riesgo (Stock crítico)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Stock mínimo</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($critical_products as $prod): ?>
                            <tr>
                                <td><?php echo $prod['id']; ?></td>
                                <td><?php echo htmlspecialchars($prod['code']); ?></td>
                                <td><strong><?php echo htmlspecialchars($prod['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($prod['category']); ?></td>
                                <td><span class="badge badge-danger"><?php echo $prod['quantity']; ?></span></td>
                                <td><span class="badge badge-warning"><?php echo $prod['stock_minimo']; ?></span></td>
                                <td>
                                    <img src="<?php echo !empty($prod['imagen']) ? htmlspecialchars($prod['imagen']) : 'assets/img/products/default.png'; ?>" alt="Imagen" style="width:48px; height:48px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
                                </td>
                                <td>
                                    <a href="index.php?route=edit_product&id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-info" title="Editar"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="animate__animated animate__fadeIn">
        <i class="fas fa-boxes"></i> Lista de Productos
    </h2>
    <?php if ($isAdmin): ?>
    <a href="index.php?route=create_product" class="btn btn-primary animate__animated animate__fadeIn">
        <i class="fas fa-plus"></i> Nuevo Producto
    </a>
    <?php endif; ?>
</div>

<!-- Search Form -->
<div class="card mb-4 animate__animated animate__fadeIn">
    <div class="card-body">
        <form method="GET" action="index.php" class="row align-items-end">
            <input type="hidden" name="route" value="products">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label for="search"><i class="fas fa-search"></i> Buscar Productos</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Buscar por nombre, código o categoría..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label for="category"><i class="fas fa-tags"></i> Categoría</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">Todas las categorías</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"
                                    <?php echo (isset($_GET['category']) && $_GET['category'] === $category) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <?php if ($isAdmin): ?>
        <!-- New Category Form -->
        <form method="POST" action="index.php?route=products" class="row mt-3 align-items-end">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label for="new_category"><i class="fas fa-plus-circle"></i> Nueva Categoría</label>
                    <input type="text" class="form-control" id="new_category" name="new_category" placeholder="Nombre de la nueva categoría">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-plus"></i> Crear Categoría
                </button>
            </div>
            <div class="col-md-3">
                <?php if(isset($cat_message)): ?>
                    <div class="alert alert-info py-2 px-3 mb-0 mt-2 animate__animated animate__fadeIn">
                        <?php echo htmlspecialchars($cat_message); ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<div class="card animate__animated animate__fadeIn">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-barcode"></i> Código</th>
                        <th><i class="fas fa-tag"></i> Nombre</th>
                        <th><i class="fas fa-align-left"></i> Descripción</th>
                        <th><i class="fas fa-tags"></i> Categoría</th>
                        <th><i class="fas fa-dollar-sign"></i> Precio</th>
                        <th><i class="fas fa-cubes"></i> Cantidad</th>
                        <?php if ($isAdmin): ?><th><i class="fas fa-exclamation-triangle"></i> Stock mínimo</th><?php endif; ?>
                        <th>Imagen</th>
                        <th><i class="fas fa-calendar"></i> Fecha de Creación</th>
                        <?php if ($isAdmin): ?><th><i class="fas fa-cogs"></i> Acciones</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($products)): ?>
                        <tr>
                            <td colspan="<?php echo $isAdmin ? '11' : '9'; ?>" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> No hay productos registrados
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($products as $product): ?>
                            <tr class="animate__animated animate__fadeIn">
                                <td><?php echo $product['id']; ?></td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo htmlspecialchars($product['code']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($product['category']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?php echo $product['quantity']; ?> unidades
                                    </span>
                                </td>
                                <?php if ($isAdmin): ?><td><span class="badge badge-warning"><?php echo $product['stock_minimo']; ?></span></td><?php endif; ?>
                                <td>
                                    <img src="<?php echo !empty($product['imagen']) ? htmlspecialchars($product['imagen']) : 'assets/img/products/default.png'; ?>" alt="Imagen" style="width:48px; height:48px; object-fit:cover; border-radius:6px; border:1px solid #ccc;">
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($product['created'])); ?>
                                    </small>
                                </td>
                                <?php if ($isAdmin): ?>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?route=edit_product&id=<?php echo $product['id']; ?>" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?route=delete_product&id=<?php echo $product['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar este producto?')"
                                           data-bs-toggle="tooltip" 
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Initialize tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

<?php include 'views/footer.php'; ?> 