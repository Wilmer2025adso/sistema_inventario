<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $empresa_id;
    public $code;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $stock_minimo;
    public $category;
    public $created;
    public $updated;
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    empresa_id = :empresa_id,
                    code = :code,
                    name = :name,
                    description = :description,
                    price = :price,
                    quantity = :quantity,
                    stock_minimo = :stock_minimo,
                    imagen = :imagen,
                    category = :category";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->empresa_id = htmlspecialchars(strip_tags($this->empresa_id));
        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->stock_minimo = htmlspecialchars(strip_tags($this->stock_minimo));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));

        // Bind values
        $stmt->bindParam(":empresa_id", $this->empresa_id);
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":stock_minimo", $this->stock_minimo);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":category", $this->category);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($empresa_id = null) {
        $query = "SELECT * FROM " . $this->table_name;
        if($empresa_id) {
            $query .= " WHERE empresa_id = :empresa_id";
        }
        $query .= " ORDER BY created DESC";
        $stmt = $this->conn->prepare($query);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        $stmt->execute();
        return $stmt;
    }

    public function search($search_term, $empresa_id = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        if($empresa_id) {
            $query .= " AND empresa_id = :empresa_id";
        }
        $query .= " AND (name LIKE :search_term OR code LIKE :search_term OR category LIKE :search_term)";
        $query .= " ORDER BY created DESC";

        $stmt = $this->conn->prepare($query);
        
        // Add wildcards to search term
        $search_term = "%" . $search_term . "%";
        $stmt->bindParam(":search_term", $search_term);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->code = $row['code'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->quantity = $row['quantity'];
            $this->stock_minimo = $row['stock_minimo'];
            $this->category = $row['category'];
            $this->created = $row['created'];
            $this->updated = $row['updated'];
            $this->imagen = $row['imagen'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    code = :code,
                    name = :name,
                    description = :description,
                    price = :price,
                    quantity = :quantity,
                    stock_minimo = :stock_minimo,
                    imagen = :imagen,
                    category = :category
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->stock_minimo = htmlspecialchars(strip_tags($this->stock_minimo));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":stock_minimo", $this->stock_minimo);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getCategories() {
        $query = "SELECT name FROM categories ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getCriticalStock($empresa_id = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE quantity <= stock_minimo";
        if($empresa_id) {
            $query .= " AND empresa_id = :empresa_id";
        }
        $query .= " ORDER BY quantity ASC";
        $stmt = $this->conn->prepare($query);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        $stmt->execute();
        return $stmt;
    }
} 