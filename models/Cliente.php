<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $id;
    public $empresa_id;
    public $nombre;
    public $email;
    public $telefono;
    public $direccion;
    public $tipo;
    public $documento;
    public $estado;
    public $created;
    public $updated;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    empresa_id = :empresa_id,
                    nombre = :nombre,
                    email = :email,
                    telefono = :telefono,
                    direccion = :direccion,
                    tipo = :tipo,
                    documento = :documento,
                    estado = :estado";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->empresa_id = htmlspecialchars(strip_tags($this->empresa_id));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->documento = htmlspecialchars(strip_tags($this->documento));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Bind values
        $stmt->bindParam(":empresa_id", $this->empresa_id);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":documento", $this->documento);
        $stmt->bindParam(":estado", $this->estado);

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

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->empresa_id = $row['empresa_id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->direccion = $row['direccion'];
            $this->tipo = $row['tipo'];
            $this->documento = $row['documento'];
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
                    email = :email,
                    telefono = :telefono,
                    direccion = :direccion,
                    tipo = :tipo,
                    documento = :documento,
                    estado = :estado
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->documento = htmlspecialchars(strip_tags($this->documento));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":documento", $this->documento);
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

    public function search($search_term, $empresa_id = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        if($empresa_id) {
            $query .= " AND empresa_id = :empresa_id";
        }
        $query .= " AND (nombre LIKE :search_term OR email LIKE :search_term OR documento LIKE :search_term)";
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

    public function getStats($empresa_id = null) {
        $query = "SELECT 
                    COUNT(*) as total_clientes,
                    COUNT(CASE WHEN tipo = 'individual' THEN 1 END) as clientes_individuales,
                    COUNT(CASE WHEN tipo = 'empresa' THEN 1 END) as clientes_empresas,
                    COUNT(CASE WHEN estado = 'activo' THEN 1 END) as clientes_activos
                  FROM " . $this->table_name;
        if($empresa_id) {
            $query .= " WHERE empresa_id = :empresa_id";
        }
        
        $stmt = $this->conn->prepare($query);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 