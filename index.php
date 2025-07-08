<?php
session_start();
require_once 'controllers/UserController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/MovimientoController.php';
require_once 'controllers/EmpresaController.php';
require_once 'controllers/ClienteController.php';
require_once 'controllers/VentaController.php';

$userController = new UserController();
$productController = new ProductController();
$movimientoController = new MovimientoController();
$empresaController = new EmpresaController();
$clienteController = new ClienteController();
$ventaController = new VentaController();

// Simple router
$route = isset($_GET['route']) ? $_GET['route'] : 'dashboard';

// Check if user is logged in for protected routes
$protected_routes = ['dashboard', 'products', 'create_product', 'edit_product', 'delete_product', 
                    'clientes', 'create_cliente', 'edit_cliente', 'delete_cliente',
                    'empresas', 'create_empresa', 'edit_empresa', 'delete_empresa',
                    'ventas', 'create_venta', 'edit_venta', 'delete_venta',
                    'movimiento', 'historial', 'manage_users'];

if(in_array($route, $protected_routes) && !$userController->isLoggedIn()) {
    header('Location: index.php?route=login');
    exit;
}

// Handle routes
switch($route) {
    case 'home':
        include 'views/home.php';
        break;
    case 'dashboard':
        // Get dashboard statistics
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
        
        // Get general stats
        $stats = $empresaController->getStats($empresa_id);
        
        // Get sales stats
        $ventas_stats = $ventaController->getStats($empresa_id, 'mes');
        
        // Get critical stock products
        $critical_result = $productController->getCriticalStock();
        $critical_products = $critical_result['data'];
        
        // Get sales by day for chart
        $ventas_por_dia_result = $ventaController->getVentasPorDia($empresa_id, 7);
        $ventas_por_dia = $ventas_por_dia_result['data'];
        
        // Get top selling products
        $productos_mas_vendidos_result = $ventaController->getProductosMasVendidos($empresa_id, 5);
        $productos_mas_vendidos = $productos_mas_vendidos_result['data'];
        
        include 'views/dashboard.php';
        break;
    case 'login':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $userController->login($_POST['email'], $_POST['password']);
            if($result['success']) {
                header('Location: index.php?route=dashboard');
                exit;
            }
            $error = $result['message'];
        }
        include 'views/login.php';
        break;
    case 'register':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $userController->register(
                $_POST['name'],
                $_POST['email'],
                $_POST['password'],
                $_POST['rol']
            );
            if($result['success']) {
                header('Location: index.php?route=login');
                exit;
            }
            $error = $result['message'];
        }
        include 'views/register.php';
        break;
    case 'logout':
        $userController->logout();
        header('Location: index.php?route=login');
        exit;
    case 'products':
        // Get categories for the dropdown
        $categories_result = $productController->getCategories();
        $categories = $categories_result['data'];
        
        // Handle new category creation
        if(isset($_POST['new_category']) && !empty($_POST['new_category'])) {
            $cat_result = $productController->createCategory($_POST['new_category']);
            // Refresh categories after adding
            $categories_result = $productController->getCategories();
            $categories = $categories_result['data'];
            $cat_message = $cat_result['message'];
        }
        // Handle search and category filter
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        if($category === '') {
            $category = '';
        }
        $result = $productController->search($search, $category);
        $products = $result['data'];
        // Get critical stock products
        $critical_result = $productController->getCriticalStock();
        $critical_products = $critical_result['data'];
        include 'views/products.php';
        break;
    case 'create_product':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $productController->create(
                $_POST['name'],
                $_POST['description'],
                $_POST['price'],
                $_POST['quantity'],
                $_POST['code'],
                $_POST['category'],
                $_POST['stock_minimo'],
                isset($_FILES['imagen']) ? $_FILES['imagen'] : null
            );
            if($result['success']) {
                header('Location: index.php?route=products');
                exit;
            }
            $error = $result['message'];
        }
        // Get categories for the dropdown
        $categories_result = $productController->getCategories();
        $categories = $categories_result['data'];
        include 'views/create_product.php';
        break;
    case 'edit_product':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $productController->update(
                $_POST['id'],
                $_POST['name'],
                $_POST['description'],
                $_POST['price'],
                $_POST['quantity'],
                $_POST['code'],
                $_POST['category'],
                $_POST['stock_minimo'],
                isset($_FILES['imagen']) ? $_FILES['imagen'] : null
            );
            if($result['success']) {
                header('Location: index.php?route=products');
                exit;
            }
            $error = $result['message'];
        }
        $result = $productController->readOne($_GET['id']);
        if($result['success']) {
            $product = $result['data'];
            // Get categories for the dropdown
            $categories_result = $productController->getCategories();
            $categories = $categories_result['data'];
            include 'views/edit_product.php';
        } else {
            header('Location: index.php?route=products');
            exit;
        }
        break;
    case 'delete_product':
        if(isset($_GET['id'])) {
            $productController->delete($_GET['id']);
        }
        header('Location: index.php?route=products');
        exit;
    case 'manage_users':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['rol'])) {
            $userController->updateUserRole($_POST['user_id'], $_POST['rol']);
        }
        $users = $userController->getAllUsers();
        include 'views/manage_users.php';
        break;
    case 'movimiento':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        // Obtener productos para el select
        $result = $productController->read();
        $productos = $result['data'];
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mov = $movimientoController->registrar(
                $_POST['producto_id'],
                $_SESSION['user_id'],
                $_POST['tipo'],
                $_POST['cantidad']
            );
            $mensaje = $mov['message'];
        }
        include 'views/movimiento.php';
        break;
    case 'historial':
        // Obtener productos y usuarios para los filtros
        $result = $productController->read();
        $productos = $result['data'];
        $usuarios = $userController->getAllUsers();
        // Filtros
        $filtros = [
            'producto_id' => isset($_GET['producto_id']) ? $_GET['producto_id'] : '',
            'tipo' => isset($_GET['tipo']) ? $_GET['tipo'] : '',
            'usuario_id' => isset($_GET['usuario_id']) ? $_GET['usuario_id'] : ''
        ];
        $movimientos = $movimientoController->historial($filtros);
        include 'views/historial.php';
        break;
    // Clientes routes
    case 'clientes':
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
        
        if(!empty($search)) {
            $result = $clienteController->search($search, $empresa_id);
        } else {
            $result = $clienteController->read($empresa_id);
        }
        $clientes = $result['data'];
        $clientes_stats = $clienteController->getStats($empresa_id);
        include 'views/clientes.php';
        break;
    case 'create_cliente':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : 1; // Default to first empresa
            $result = $clienteController->create(
                $empresa_id,
                $_POST['nombre'],
                $_POST['email'],
                $_POST['telefono'],
                $_POST['direccion'],
                $_POST['tipo'],
                $_POST['documento']
            );
            if($result['success']) {
                header('Location: index.php?route=clientes');
                exit;
            }
            $error = $result['message'];
        }
        include 'views/create_cliente.php';
        break;
    case 'edit_cliente':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $clienteController->update(
                $_POST['id'],
                $_POST['nombre'],
                $_POST['email'],
                $_POST['telefono'],
                $_POST['direccion'],
                $_POST['tipo'],
                $_POST['documento'],
                $_POST['estado']
            );
            if($result['success']) {
                header('Location: index.php?route=clientes');
                exit;
            }
            $error = $result['message'];
        }
        $result = $clienteController->readOne($_GET['id']);
        if($result['success']) {
            $cliente = $result['data'];
            include 'views/create_cliente.php';
        } else {
            header('Location: index.php?route=clientes');
            exit;
        }
        break;
    case 'delete_cliente':
        if(isset($_GET['id'])) {
            $clienteController->delete($_GET['id']);
        }
        header('Location: index.php?route=clientes');
        exit;
    // Empresas routes (only for super admin)
    case 'empresas':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'super_admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        $result = $empresaController->read();
        $empresas = $result['data'];
        
        // Get statistics
        $total_empresas = count($empresas);
        $empresas_activas = 0;
        $empresas_inactivas = 0;
        $total_usuarios = 0;
        
        foreach($empresas as $empresa) {
            if($empresa['estado'] === 'activo') {
                $empresas_activas++;
            } else {
                $empresas_inactivas++;
            }
        }
        
        // Get total users
        $users = $userController->getAllUsers();
        $total_usuarios = count($users);
        
        include 'views/empresas.php';
        break;
    case 'create_empresa':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'super_admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $empresaController->create(
                $_POST['nombre'],
                $_POST['ruc'],
                $_POST['direccion'],
                $_POST['telefono'],
                $_POST['email'],
                isset($_FILES['logo']) ? $_FILES['logo'] : null
            );
            if($result['success']) {
                header('Location: index.php?route=empresas');
                exit;
            }
            $error = $result['message'];
        }
        include 'views/create_empresa.php';
        break;
    case 'edit_empresa':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'super_admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $empresaController->update(
                $_POST['id'],
                $_POST['nombre'],
                $_POST['ruc'],
                $_POST['direccion'],
                $_POST['telefono'],
                $_POST['email'],
                isset($_FILES['logo']) ? $_FILES['logo'] : null,
                $_POST['estado']
            );
            if($result['success']) {
                header('Location: index.php?route=empresas');
                exit;
            }
            $error = $result['message'];
        }
        $result = $empresaController->readOne($_GET['id']);
        if($result['success']) {
            $empresa = $result['data'];
            include 'views/create_empresa.php';
        } else {
            header('Location: index.php?route=empresas');
            exit;
        }
        break;
    case 'delete_empresa':
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'super_admin') {
            header('Location: index.php?route=dashboard');
            exit;
        }
        if(isset($_GET['id'])) {
            $empresaController->delete($_GET['id']);
        }
        header('Location: index.php?route=empresas');
        exit;
    // Ventas routes
    case 'ventas':
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
        $result = $ventaController->read($empresa_id);
        $ventas = $result['data'];
        include 'views/ventas.php';
        break;
    case 'create_venta':
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : 1;
            $productos = [];
            if (isset($_POST['productos_json'])) {
                $productos = json_decode($_POST['productos_json'], true);
            }
            $total = isset($_POST['total']) ? $_POST['total'] : 0;

            $result = $ventaController->create(
                $empresa_id,
                $_POST['cliente_id'],
                $_SESSION['user_id'],
                $productos,
                $total
            );
            if($result['success']) {
                header('Location: index.php?route=ventas');
                exit;
            }
            $error = $result['message'];
        }
        // Get products and clients for the form
        $productos_result = $productController->read();
        $productos = $productos_result['data'];
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : 1;
        $clientes_result = $clienteController->read($empresa_id);
        $clientes = $clientes_result['data'];
        include 'views/create_venta.php';
        break;
    default:
        include 'views/dashboard.php';
        break;
} 