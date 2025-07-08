<?php include 'views/header.php'; ?>
<div class="container">
    <h2 class="mb-4"><i class="fas fa-users-cog"></i> Administración de Usuarios</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="POST" action="index.php?route=manage_users" class="form-inline" onsubmit="return confirm('¿Estás seguro de cambiar el rol de este usuario?');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <select name="rol" class="form-control mr-2" <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                        <option value="vendedor" <?php echo $user['rol'] === 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                                        <option value="admin" <?php echo $user['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                                    </select>
                                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                                    <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                            <td>
                                <?php if($user['id'] == $_SESSION['user_id']): ?>
                                    <span class="badge badge-info">Tú</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?> 