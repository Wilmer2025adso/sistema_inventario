<?php
class Empresa {
    private $conn;
    private $table_name = "empresas";

    public $id;
    public $nombre;
    public $ruc;
    public $direccion;
    public $telefono;
    public $email;
    public $logo;
    public $estado;
    public $created;
    public $updated;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    nombre = :nombre,
                    ruc = :ruc,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    logo = :logo,
                    estado = :estado";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->logo = htmlspecialchars(strip_tags($this->logo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Bind values
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":logo", $this->logo);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created DESC";
        $stmt = $this->conn->prepare($query);
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
            $this->nombre = $row['nombre'];
            $this->ruc = $row['ruc'];
            $this->direccion = $row['direccion'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->logo = $row['logo'];
            $this->estado = $row['estado'];
            $this->created = $row['created'];
            $this->updated = $row['updated'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    nombre = :nombre,
                    ruc = :ruc,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    logo = :logo,
                    estado = :estado
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->logo = htmlspecialchars(strip_tags($this->logo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":logo", $this->logo);
        $stmt->bindParam(":estado", $this->estado);
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

    public function getStats() {
        $query = "SELECT 
                    COUNT(DISTINCT p.id) as total_productos,
                    COUNT(DISTINCT c.id) as total_clientes,
                    COUNT(DISTINCT u.id) as total_usuarios,
                    SUM(p.quantity * p.price) as valor_inventario
                  FROM " . $this->table_name . " e
                  LEFT JOIN products p ON e.id = p.empresa_id
                  LEFT JOIN clientes c ON e.id = c.empresa_id
                  LEFT JOIN users u ON e.id = u.empresa_id
                  WHERE e.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rucExists($ruc, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE ruc = :ruc";
        if($exclude_id) {
            $query .= " AND id != :exclude_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ruc", $ruc);
        if($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
} 