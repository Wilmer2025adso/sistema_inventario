<?php
class Venta {
    private $conn;
    private $table_name = "ventas";

    public $id;
    public $empresa_id;
    public $cliente_id;
    public $usuario_id;
    public $total;
    public $estado;
    public $fecha;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    empresa_id = :empresa_id,
                    cliente_id = :cliente_id,
                    usuario_id = :usuario_id,
                    total = :total,
                    estado = :estado";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->empresa_id = htmlspecialchars(strip_tags($this->empresa_id));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Bind values
        $stmt->bindParam(":empresa_id", $this->empresa_id);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function read($empresa_id = null, $usuario_id = null) {
        $query = "SELECT v.*, c.nombre as cliente_nombre, u.name as usuario_nombre 
                  FROM " . $this->table_name . " v
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  LEFT JOIN users u ON v.usuario_id = u.id WHERE 1=1";
        if($empresa_id) {
            $query .= " AND v.empresa_id = :empresa_id";
        }
        if($usuario_id) {
            $query .= " AND v.usuario_id = :usuario_id";
        }
        $query .= " ORDER BY v.fecha DESC";
        $stmt = $this->conn->prepare($query);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        if($usuario_id) {
            $stmt->bindParam(":usuario_id", $usuario_id);
        }
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT v.*, c.nombre as cliente_nombre, u.name as usuario_nombre 
                  FROM " . $this->table_name . " v
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  LEFT JOIN users u ON v.usuario_id = u.id
                  WHERE v.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->empresa_id = $row['empresa_id'];
            $this->cliente_id = $row['cliente_id'];
            $this->usuario_id = $row['usuario_id'];
            $this->total = $row['total'];
            $this->estado = $row['estado'];
            $this->fecha = $row['fecha'];
            return $row;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    cliente_id = :cliente_id,
                    total = :total,
                    estado = :estado
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->total = htmlspecialchars(strip_tags($this->total));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":total", $this->total);
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

    public function getStats($empresa_id = null, $periodo = 'mes') {
        $date_filter = '';
        switch($periodo) {
            case 'hoy':
                $date_filter = "AND DATE(v.fecha) = CURDATE()";
                break;
            case 'semana':
                $date_filter = "AND v.fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case 'mes':
                $date_filter = "AND v.fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                break;
            case 'año':
                $date_filter = "AND v.fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                break;
        }

        $query = "SELECT 
                    COUNT(*) as total_ventas,
                    SUM(CASE WHEN v.estado = 'completada' THEN v.total ELSE 0 END) as total_ingresos,
                    AVG(CASE WHEN v.estado = 'completada' THEN v.total ELSE NULL END) as promedio_venta,
                    COUNT(CASE WHEN v.estado = 'pendiente' THEN 1 END) as ventas_pendientes,
                    COUNT(CASE WHEN v.estado = 'cancelada' THEN 1 END) as ventas_canceladas
                  FROM " . $this->table_name . " v
                  WHERE 1=1 " . $date_filter;
        
        if($empresa_id) {
            $query .= " AND v.empresa_id = :empresa_id";
        }
        
        $stmt = $this->conn->prepare($query);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVentasPorDia($empresa_id = null, $dias = 7) {
        $query = "SELECT 
                    DATE(v.fecha) as fecha,
                    COUNT(*) as total_ventas,
                    SUM(CASE WHEN v.estado = 'completada' THEN v.total ELSE 0 END) as ingresos
                  FROM " . $this->table_name . " v
                  WHERE v.fecha >= DATE_SUB(NOW(), INTERVAL :dias DAY)";
        
        if($empresa_id) {
            $query .= " AND v.empresa_id = :empresa_id";
        }
        
        $query .= " GROUP BY DATE(v.fecha) ORDER BY fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dias", $dias);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        $stmt->execute();
        return $stmt;
    }

    public function getProductosMasVendidos($empresa_id = null, $limite = 10) {
        $query = "SELECT 
                    p.name as producto,
                    SUM(vd.cantidad) as total_vendido,
                    SUM(vd.subtotal) as total_ingresos
                  FROM venta_detalles vd
                  JOIN ventas v ON vd.venta_id = v.id
                  JOIN products p ON vd.producto_id = p.id
                  WHERE v.estado = 'completada'";
        
        if($empresa_id) {
            $query .= " AND v.empresa_id = :empresa_id";
        }
        
        $query .= " GROUP BY p.id, p.name ORDER BY total_vendido DESC LIMIT :limite";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limite", $limite, PDO::PARAM_INT);
        if($empresa_id) {
            $stmt->bindParam(":empresa_id", $empresa_id);
        }
        $stmt->execute();
        return $stmt;
    }
} 