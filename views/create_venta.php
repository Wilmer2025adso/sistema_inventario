<?php include 'views/header.php'; ?>
<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<div class="container mt-4">
    <h2>Nueva Venta</h2>
    <form action="index.php?action=store_venta" method="POST">
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class="form-select" id="cliente_id" name="cliente_id" required>
                <option value="">Seleccione un cliente</option>
                <?php if (isset($clientes) && is_array($clientes)): ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>"><?= $cliente['nombre'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select class="form-select" id="producto_id" name="producto_id">
                <option value="">Seleccione un producto</option>
                <?php if (isset($productos) && is_array($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id'] ?>" data-precio="<?= $producto['price'] ?>">
                            <?= $producto['name'] ?> (Stock: <?= $producto['quantity'] ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1">
        </div>
        <button type="button" class="btn btn-secondary mb-3" id="agregar_producto">Agregar Producto</button>
        <div class="mb-3">
            <h5>Productos en la venta</h5>
            <table class="table" id="tabla_venta">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se agregarán los productos seleccionados -->
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th id="total_venta">$0.00</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <input type="hidden" name="productos_json" id="productos_json">
        <input type="hidden" name="total" id="total">
        <button type="submit" class="btn btn-primary">Registrar Venta</button>
        <a href="index.php?action=ventas" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
// Lógica JS para agregar productos a la tabla y serializar en JSON
const productos = [];
const agregarBtn = document.getElementById('agregar_producto');
const tablaVenta = document.getElementById('tabla_venta').querySelector('tbody');
const productosJson = document.getElementById('productos_json');
const totalVenta = document.getElementById('total_venta');
const totalInput = document.getElementById('total');

agregarBtn.addEventListener('click', function() {
    const productoSelect = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const productoId = productoSelect.value;
    const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
    const precio = parseFloat(productoSelect.options[productoSelect.selectedIndex].getAttribute('data-precio')) || 0;
    const cantidad = parseInt(cantidadInput.value);
    if (!productoId || !cantidad || cantidad < 1) {
        alert('Seleccione un producto y cantidad válida.');
        return;
    }
    // Verificar si ya está en la lista
    if (productos.some(p => p.producto_id === productoId)) {
        alert('Este producto ya fue agregado.');
        return;
    }
    const subtotal = precio * cantidad;
    productos.push({ producto_id: productoId, cantidad: cantidad, nombre: productoNombre, precio: precio, subtotal: subtotal });
    renderTabla();
    productoSelect.value = '';
    cantidadInput.value = '';
});

function renderTabla() {
    tablaVenta.innerHTML = '';
    let total = 0;
    productos.forEach((p, idx) => {
        total += p.subtotal;
        const row = document.createElement('tr');
        row.innerHTML = `<td>${p.nombre}</td><td>${p.cantidad}</td><td>$${p.precio.toFixed(2)}</td><td>$${p.subtotal.toFixed(2)}</td><td><button type='button' class='btn btn-danger btn-sm' onclick='eliminarProducto(${idx})'>Eliminar</button></td>`;
        tablaVenta.appendChild(row);
    });
    totalVenta.textContent = `$${total.toFixed(2)}`;
    totalInput.value = total.toFixed(2);
    // Serializar solo los datos necesarios para el backend
    productosJson.value = JSON.stringify(productos.map(p => ({ id: p.producto_id, cantidad: p.cantidad, precio: p.precio, subtotal: p.subtotal })));
}

window.eliminarProducto = function(idx) {
    productos.splice(idx, 1);
    renderTabla();
}
</script>
<?php include 'views/footer.php'; ?> 