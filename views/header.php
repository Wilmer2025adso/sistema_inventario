<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-dark navbar-topbar">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-hamburger mr-3" id="sidebarToggle" aria-label="Menú principal">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <a class="navbar-brand animate__animated animate__fadeIn" href="index.php">
                    <i class="fas fa-store"></i> Sistema de Inventario
                </a>
            </div>
            <div class="d-flex align-items-center">
                <button class="theme-toggle nav-link" id="themeToggle" title="Cambiar tema">
                    <i class="fas fa-moon"></i>
                </button>
                <?php if(isset($_SESSION['user_id'])): ?>
                <div class="dropdown ml-3">
                    <a class="nav-link dropdown-toggle user-dropdown" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="index.php?route=profile">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php?route=logout">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- Sidebar Offcanvas -->
    <div id="sidebarOffcanvas" class="sidebar-offcanvas">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <span class="sidebar-title"><i class="fas fa-store"></i> Sistema de Inventario</span>
            <button class="btn btn-close-sidebar" id="sidebarClose" aria-label="Cerrar menú">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="sidebar-menu">
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="index.php?route=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="index.php?route=products"><i class="fas fa-boxes"></i> Productos</a></li>
                <li><a href="index.php?route=clientes"><i class="fas fa-users"></i> Clientes</a></li>
                <li><a href="index.php?route=ventas"><i class="fas fa-shopping-cart"></i> Ventas</a></li>
                <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
                    <li><a href="index.php?route=create_product"><i class="fas fa-plus-circle"></i> Nuevo Producto</a></li>
                    <li><a href="index.php?route=create_cliente"><i class="fas fa-user-plus"></i> Nuevo Cliente</a></li>
                    <li><a href="index.php?route=create_venta"><i class="fas fa-cart-plus"></i> Nueva Venta</a></li>
                    <li><a href="index.php?route=manage_users"><i class="fas fa-users-cog"></i> Administrar usuarios</a></li>
                    <li><a href="index.php?route=movimiento"><i class="fas fa-exchange-alt"></i> Registrar Movimiento</a></li>
                <?php endif; ?>
                <?php if(isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'super_admin'): ?>
                    <li><a href="index.php?route=empresas"><i class="fas fa-building"></i> Empresas</a></li>
                <?php endif; ?>
                <li><a href="index.php?route=historial"><i class="fas fa-history"></i> Historial de Movimientos</a></li>
            <?php else: ?>
                <li><a href="index.php?route=login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                <li><a href="index.php?route=register"><i class="fas fa-user-plus"></i> Registrarse</a></li>
            <?php endif; ?>
        </ul>
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="sidebar-user mt-auto">
            <hr>
            <div class="sidebar-user-info">
                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </div>
            <a href="index.php?route=profile" class="btn btn-link btn-block"><i class="fas fa-user"></i> Mi Perfil</a>
            <a href="index.php?route=logout" class="btn btn-danger btn-block"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
        <?php endif; ?>
    </div>
    <div id="sidebarOverlay" class="sidebar-overlay"></div>
    <div class="container mt-4">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

<script>
// Theme handling
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const htmlElement = document.documentElement;
    const themeIcon = themeToggle.querySelector('i');
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
    themeToggle.addEventListener('click', function() {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    function updateThemeIcon(theme) {
        themeIcon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
    }

    // Sidebar Offcanvas
    const sidebar = document.getElementById('sidebarOffcanvas');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    function openSidebar() {
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.classList.add('sidebar-open');
    }
    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    }
    sidebarToggle.addEventListener('click', openSidebar);
    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);
    // Cerrar sidebar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
});
</script> 