<?php
require_once 'models/User.php';
require_once 'config/database.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($name, $email, $password, $rol) {
        // Validate input
        if(empty($name) || empty($email) || empty($password)) {
            return ["success" => false, "message" => "Todos los campos son requeridos"];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        if(strlen($password) < 6) {
            return ["success" => false, "message" => "La contraseña debe tener al menos 6 caracteres"];
        }

        // Check if email exists
        if($this->user->emailExists($email)) {
            return ["success" => false, "message" => "El email ya está registrado"];
        }

        // Set user properties
        $this->user->name = $name;
        $this->user->email = $email;
        $this->user->password = $password;
        $this->user->rol = $rol;

        // Create user
        if($this->user->create()) {
            return ["success" => true, "message" => "Usuario registrado exitosamente"];
        }
        return ["success" => false, "message" => "Error al registrar usuario"];
    }

    public function login($email, $password) {
        // Validate input
        if(empty($email) || empty($password)) {
            return ["success" => false, "message" => "Email y contraseña son requeridos"];
        }

        // Attempt to login
        $result = $this->user->login($email, $password);
        if($result) {
            // Store user data in session
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['user_name'] = $result['name'];
            $_SESSION['user_email'] = $result['email'];
            $_SESSION['user_rol'] = $result['rol'];
            return ["success" => true, "message" => "Login exitoso"];
        }
        return ["success" => false, "message" => "Credenciales inválidas"];
    }

    public function logout() {
        // Destroy session
        session_destroy();
        return ["success" => true, "message" => "Sesión cerrada exitosamente"];
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getAllUsers() {
        $db = $this->db;
        $stmt = $db->prepare("SELECT id, name, email, rol FROM users ORDER BY id ASC");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    public function updateUserRole($userId, $newRole) {
        $db = $this->db;
        $stmt = $db->prepare("UPDATE users SET rol = :rol WHERE id = :id");
        $stmt->bindParam(':rol', $newRole);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
} 