<?php include 'views/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus"></i> 
                    <?php echo isset($cliente) ? 'Editar Cliente' : 'Nuevo Cliente'; ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?route=<?php echo isset($cliente) ? 'edit_cliente&id=' . $cliente['id'] : 'create_cliente'; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">
                                    <i class="fas fa-user"></i> Nombre Completo *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo isset($cliente) ? htmlspecialchars($cliente['nombre']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($cliente) ? htmlspecialchars($cliente['email']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo isset($cliente) ? htmlspecialchars($cliente['telefono']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo">
                                    <i class="fas fa-tags"></i> Tipo de Cliente *
                                </label>
                                <select class="form-control" id="tipo" name="tipo" required>
                                    <option value="">Seleccione el tipo</option>
                                    <option value="individual" <?php echo (isset($cliente) && $cliente['tipo'] === 'individual') ? 'selected' : ''; ?>>Individual</option>
                                    <option value="empresa" <?php echo (isset($cliente) && $cliente['tipo'] === 'empresa') ? 'selected' : ''; ?>>Empresa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="documento">
                                    <i class="fas fa-id-card"></i> Documento
                                </label>
                                <input type="text" class="form-control" id="documento" name="documento" 
                                       value="<?php echo isset($cliente) ? htmlspecialchars($cliente['documento']) : ''; ?>"
                                       placeholder="DNI, RUC, etc.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-toggle-on"></i> Estado
                                </label>
                                <select class="form-control" id="estado" name="estado">
                                    <option value="activo" <?php echo (isset($cliente) && $cliente['estado'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
                                    <option value="inactivo" <?php echo (isset($cliente) && $cliente['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion">
                            <i class="fas fa-map-marker-alt"></i> Dirección
                        </label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" 
                                  placeholder="Dirección completa del cliente"><?php echo isset($cliente) ? htmlspecialchars($cliente['direccion']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 
                            <?php echo isset($cliente) ? 'Actualizar Cliente' : 'Crear Cliente'; ?>
                        </button>
                        <a href="index.php?route=clientes" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario
    const form = document.querySelector('form');
    const tipoSelect = document.getElementById('tipo');
    const documentoInput = document.getElementById('documento');

    // Cambiar placeholder del documento según el tipo
    tipoSelect.addEventListener('change', function() {
        if (this.value === 'individual') {
            documentoInput.placeholder = 'DNI, CE, etc.';
        } else if (this.value === 'empresa') {
            documentoInput.placeholder = 'RUC, etc.';
        } else {
            documentoInput.placeholder = 'Documento';
        }
    });

    // Validación antes de enviar
    form.addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        const tipo = tipoSelect.value;
        const email = document.getElementById('email').value.trim();

        if (nombre === '') {
            e.preventDefault();
            alert('El nombre es obligatorio');
            return false;
        }

        if (tipo === '') {
            e.preventDefault();
            alert('Debe seleccionar el tipo de cliente');
            return false;
        }

        if (email !== '' && !isValidEmail(email)) {
            e.preventDefault();
            alert('El email no tiene un formato válido');
            return false;
        }
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
</script>

<?php include 'views/footer.php'; ?> 