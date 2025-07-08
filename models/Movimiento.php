<?php
class Movimiento {
    private $conn;
    private $table_name = "movimientos";

    public $id;
    public $empresa_id;
    public $producto_id;
    public $usuario_id;
    public $tipo;
    public $cantidad;
    public $stock_resultante;
    public $fecha;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        $query = "INSERT INTO " . $this->table_name . "
            (empresa_id, producto_id, usuario_id, tipo, cantidad, stock_resultante)
            VALUES (:empresa_id, :producto_id, :usuario_id, :tipo, :cantidad, :stock_resultante)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":empresa_id", $this->empresa_id);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":stock_resultante", $this->stock_resultante);
        return $stmt->execute();
    }

    public function historial($filtros = []) {
        $query = "SELECT m.*, p.name as producto, u.name as usuario FROM " . $this->table_name . " m
                  JOIN products p ON m.producto_id = p.id
                  JOIN users u ON m.usuario_id = u.id
                  WHERE 1=1";
        $params = [];
        if (!empty($filtros['producto_id'])) {
            $query .= " AND m.producto_id = :producto_id";
            $params[':producto_id'] = $filtros['producto_id'];
        }
        if (!empty($filtros['tipo'])) {
            $query .= " AND m.tipo = :tipo";
            $params[':tipo'] = $filtros['tipo'];
        }
        if (!empty($filtros['usuario_id'])) {
            $query .= " AND m.usuario_id = :usuario_id";
            $params[':usuario_id'] = $filtros['usuario_id'];
        }
        $query .= " ORDER BY m.fecha DESC";
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 