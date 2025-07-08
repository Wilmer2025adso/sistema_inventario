<?php include 'views/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-building"></i> 
                    <?php echo isset($empresa) ? 'Editar Empresa' : 'Nueva Empresa'; ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?route=<?php echo isset($empresa) ? 'edit_empresa&id=' . $empresa['id'] : 'create_empresa'; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">
                                    <i class="fas fa-building"></i> Nombre de la Empresa *
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo isset($empresa) ? htmlspecialchars($empresa['nombre']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ruc">
                                    <i class="fas fa-id-card"></i> RUC *
                                </label>
                                <input type="text" class="form-control" id="ruc" name="ruc" 
                                       value="<?php echo isset($empresa) ? htmlspecialchars($empresa['ruc']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($empresa) ? htmlspecialchars($empresa['email']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo isset($empresa) ? htmlspecialchars($empresa['telefono']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion">
                            <i class="fas fa-map-marker-alt"></i> Dirección
                        </label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" 
                                  placeholder="Dirección completa de la empresa"><?php echo isset($empresa) ? htmlspecialchars($empresa['direccion']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="logo">
                            <i class="fas fa-image"></i> Logo de la Empresa
                        </label>
                        <input type="file" class="form-control-file" id="logo" name="logo" accept="image/*">
                        <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                        <?php if(isset($empresa) && !empty($empresa['logo'])): ?>
                            <div class="mt-2">
                                <img src="<?php echo htmlspecialchars($empresa['logo']); ?>" alt="Logo actual" style="max-width: 100px; max-height: 100px;">
                                <small class="text-muted">Logo actual</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if(isset($empresa)): ?>
                    <div class="form-group">
                        <label for="estado">
                            <i class="fas fa-toggle-on"></i> Estado
                        </label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="activo" <?php echo ($empresa['estado'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
                            <option value="inactivo" <?php echo ($empresa['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 
                            <?php echo isset($empresa) ? 'Actualizar Empresa' : 'Crear Empresa'; ?>
                        </button>
                        <a href="index.php?route=empresas" class="btn btn-secondary">
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
    const rucInput = document.getElementById('ruc');
    const emailInput = document.getElementById('email');

    // Validación antes de enviar
    form.addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        const ruc = rucInput.value.trim();
        const email = emailInput.value.trim();

        if (nombre === '') {
            e.preventDefault();
            alert('El nombre de la empresa es obligatorio');
            return false;
        }

        if (ruc === '') {
            e.preventDefault();
            alert('El RUC es obligatorio');
            return false;
        }

        if (email !== '' && !isValidEmail(email)) {
            e.preventDefault();
            alert('El email no tiene un formato válido');
            return false;
        }

        // Validar archivo de logo
        const logoFile = document.getElementById('logo').files[0];
        if (logoFile) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (logoFile.size > maxSize) {
                e.preventDefault();
                alert('El archivo de logo es demasiado grande. El tamaño máximo es 2MB');
                return false;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(logoFile.type)) {
                e.preventDefault();
                alert('Solo se permiten archivos de imagen (JPG, PNG, GIF)');
                return false;
            }
        }
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Formatear RUC mientras se escribe
    rucInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        this.value = value;
    });
});
</script>

<?php include 'views/footer.php'; ?> 