<?php
require_once 'models/Movimiento.php';
require_once 'models/Product.php';
require_once 'config/database.php';

class MovimientoController {
    private $db;
    private $movimiento;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->movimiento = new Movimiento($this->db);
        $this->product = new Product($this->db);
    }

    public function registrar($producto_id, $usuario_id, $tipo, $cantidad) {
        // Obtener stock actual
        $this->product->id = $producto_id;
        $this->product->readOne();
        $stock_actual = $this->product->quantity;
        if ($tipo === 'salida' && $cantidad > $stock_actual) {
            return ["success" => false, "message" => "No hay suficiente stock para la salida."];
        }
        // Calcular stock resultante
        $stock_resultante = ($tipo === 'entrada') ? $stock_actual + $cantidad : $stock_actual - $cantidad;
        // Registrar movimiento
        $empresa_id = isset($_SESSION['empresa_id']) ? $_SESSION['empresa_id'] : 1;
        $this->movimiento->empresa_id = $empresa_id;
        $this->movimiento->producto_id = $producto_id;
        $this->movimiento->usuario_id = $usuario_id;
        $this->movimiento->tipo = $tipo;
        $this->movimiento->cantidad = $cantidad;
        $this->movimiento->stock_resultante = $stock_resultante;
        if ($this->movimiento->registrar()) {
            // Actualizar stock del producto
            $this->product->quantity = $stock_resultante;
            $this->product->update();
            return ["success" => true, "message" => "Movimiento registrado correctamente."];
        }
        return ["success" => false, "message" => "Error al registrar el movimiento."];
    }

    public function historial($filtros = []) {
        return $this->movimiento->historial($filtros);
    }
} 