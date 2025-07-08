<?php
require_once 'models/Product.php';
require_once 'config/database.php';

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }

    public function create($name, $description, $price, $quantity, $code, $category, $stock_minimo, $file = null) {
        // Manejo de imagen
        $imagen = null;
        if ($file && isset($file['tmp_name']) && $file['tmp_name']) {
            $targetDir = 'assets/img/products/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $imagen = $targetFile;
            }
        }
        // Validate input
        if(empty($name) || empty($description) || empty($price) || empty($quantity)) {
            return ["success" => false, "message" => "Todos los campos son requeridos"];
        }

        if(!is_numeric($price) || $price <= 0) {
            return ["success" => false, "message" => "El precio debe ser un número positivo"];
        }

        if(!is_numeric($quantity) || $quantity < 0) {
            return ["success" => false, "message" => "La cantidad debe ser un número no negativo"];
        }

        // Get empresa_id from session
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : 1; // Default to first empresa

        // Set product properties
        $this->product->empresa_id = $empresa_id;
        $this->product->name = $name;
        $this->product->description = $description;
        $this->product->price = $price;
        $this->product->quantity = $quantity;
        $this->product->code = $code;
        $this->product->category = $category;
        $this->product->stock_minimo = $stock_minimo;
        $this->product->imagen = $imagen;
        $this->product->created = date('Y-m-d H:i:s');

        // Create product
        if($this->product->create()) {
            return ["success" => true, "message" => "Producto creado exitosamente"];
        }
        return ["success" => false, "message" => "Error al crear producto"];
    }

    public function read() {
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
        $stmt = $this->product->read($empresa_id);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        return ["success" => true, "data" => $products];
    }

    public function search($search_term, $category = '') {
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
        $query = "SELECT * FROM products WHERE 1=1";
        $params = [];
        if($empresa_id) {
            $query .= " AND empresa_id = :empresa_id";
            $params[':empresa_id'] = $empresa_id;
        }
        if(!empty($search_term)) {
            $query .= " AND (name LIKE :search_term OR code LIKE :search_term OR category LIKE :search_term)";
            $params[':search_term'] = "%" . $search_term . "%";
        }
        if(!empty($category)) {
            $query .= " AND category = :category";
            $params[':category'] = $category;
        }
        $query .= " ORDER BY created DESC";
        $stmt = $this->db->prepare($query);
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        return ["success" => true, "data" => $products];
    }

    public function readOne($id) {
        $this->product->id = $id;
        if($this->product->readOne()) {
            return [
                "success" => true,
                "data" => [
                    "id" => $id,
                    "code" => $this->product->code,
                    "name" => $this->product->name,
                    "description" => $this->product->description,
                    "price" => $this->product->price,
                    "quantity" => $this->product->quantity,
                    "category" => $this->product->category,
                    "created" => $this->product->created,
                    "updated" => $this->product->updated
                ]
            ];
        }
        return ["success" => false, "message" => "Producto no encontrado"];
    }

    public function update($id, $name, $description, $price, $quantity, $code, $category, $stock_minimo, $file = null) {
        // Manejo de imagen
        $imagen = null;
        if ($file && isset($file['tmp_name']) && $file['tmp_name']) {
            $targetDir = 'assets/img/products/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $imagen = $targetFile;
            }
        }
        // Si no se sube nueva imagen, mantener la anterior
        $this->product->id = $id;
        $this->product->name = $name;
        $this->product->description = $description;
        $this->product->price = $price;
        $this->product->quantity = $quantity;
        $this->product->code = $code;
        $this->product->category = $category;
        $this->product->stock_minimo = $stock_minimo;
        $this->product->imagen = $imagen;

        // Update product
        if($this->product->update()) {
            return ["success" => true, "message" => "Producto actualizado exitosamente"];
        }
        return ["success" => false, "message" => "Error al actualizar producto"];
    }

    public function delete($id) {
        $this->product->id = $id;
        if($this->product->delete()) {
            return ["success" => true, "message" => "Producto eliminado exitosamente"];
        }
        return ["success" => false, "message" => "Error al eliminar producto"];
    }

    public function getCategories() {
        $stmt = $this->product->getCategories();
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['name'];
        }
        return ["success" => true, "data" => $categories];
    }

    public function createCategory($name) {
        $name = trim($name);
        if(empty($name)) {
            return ["success" => false, "message" => "El nombre de la categoría no puede estar vacío."];
        }
        $query = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $name);
        if($stmt->execute()) {
            return ["success" => true, "message" => "Categoría creada exitosamente."];
        }
        return ["success" => false, "message" => "Error al crear la categoría. Puede que ya exista."];
    }

    public function getCriticalStock() {
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : null;
        $stmt = $this->product->getCriticalStock($empresa_id);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        return ["success" => true, "data" => $products];
    }
} 