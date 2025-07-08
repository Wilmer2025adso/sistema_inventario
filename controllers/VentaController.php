<?php
require_once 'models/Venta.php';
require_once 'models/Product.php';
require_once 'config/database.php';

class VentaController {
    private $db;
    private $venta;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->venta = new Venta($this->db);
        $this->product = new Product($this->db);
    }

    public function create($empresa_id, $cliente_id, $usuario_id, $productos, $total) {
        // Validate input
        if(empty($empresa_id) || empty($cliente_id) || empty($usuario_id) || empty($productos)) {
            return ["success" => false, "message" => "Todos los campos son requeridos"];
        }

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Create venta
            $this->venta->empresa_id = $empresa_id;
            $this->venta->cliente_id = $cliente_id;
            $this->venta->usuario_id = $usuario_id;
            $this->venta->total = $total;
            $this->venta->estado = 'completada';

            $venta_id = $this->venta->create();
            if(!$venta_id) {
                throw new Exception("Error al crear la venta");
            }

            // Create venta detalles and update stock
            foreach($productos as $producto) {
                // Insert venta detalle
                $query = "INSERT INTO venta_detalles (venta_id, producto_id, cantidad, precio_unitario, subtotal) 
                         VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":venta_id", $venta_id);
                $stmt->bindParam(":producto_id", $producto['id']);
                $stmt->bindParam(":cantidad", $producto['cantidad']);
                $stmt->bindParam(":precio_unitario", $producto['precio']);
                $stmt->bindParam(":subtotal", $producto['subtotal']);
                
                if(!$stmt->execute()) {
                    throw new Exception("Error al crear detalle de venta");
                }

                // Update product stock
                $this->product->id = $producto['id'];
                $this->product->readOne();
                $nuevo_stock = $this->product->quantity - $producto['cantidad'];
                
                if($nuevo_stock < 0) {
                    throw new Exception("Stock insuficiente para el producto: " . $this->product->name);
                }

                $update_query = "UPDATE products SET quantity = :quantity WHERE id = :id";
                $update_stmt = $this->db->prepare($update_query);
                $update_stmt->bindParam(":quantity", $nuevo_stock);
                $update_stmt->bindParam(":id", $producto['id']);
                
                if(!$update_stmt->execute()) {
                    throw new Exception("Error al actualizar stock");
                }

                // Register movimiento
                $mov_query = "INSERT INTO movimientos (empresa_id, producto_id, usuario_id, tipo, cantidad, stock_resultante, motivo) 
                             VALUES (:empresa_id, :producto_id, :usuario_id, 'venta', :cantidad, :stock_resultante, 'Venta registrada')";
                $mov_stmt = $this->db->prepare($mov_query);
                $mov_stmt->bindParam(":empresa_id", $empresa_id);
                $mov_stmt->bindParam(":producto_id", $producto['id']);
                $mov_stmt->bindParam(":usuario_id", $usuario_id);
                $mov_stmt->bindParam(":cantidad", $producto['cantidad']);
                $mov_stmt->bindParam(":stock_resultante", $nuevo_stock);
                
                if(!$mov_stmt->execute()) {
                    throw new Exception("Error al registrar movimiento");
                }
            }

            // Commit transaction
            $this->db->commit();
            return ["success" => true, "message" => "Venta registrada exitosamente", "venta_id" => $venta_id];

        } catch (Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function read($empresa_id = null, $usuario_id = null) {
        $stmt = $this->venta->read($empresa_id, $usuario_id);
        $ventas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ventas[] = $row;
        }
        return ["success" => true, "data" => $ventas];
    }

    public function readOne($id) {
        $this->venta->id = $id;
        $venta_data = $this->venta->readOne();
        if($venta_data) {
            // Get venta detalles
            $query = "SELECT vd.*, p.name as producto_nombre, p.code as producto_codigo 
                     FROM venta_detalles vd
                     JOIN products p ON vd.producto_id = p.id
                     WHERE vd.venta_id = :venta_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":venta_id", $id);
            $stmt->execute();
            
            $detalles = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $detalles[] = $row;
            }
            
            $venta_data['detalles'] = $detalles;
            return ["success" => true, "data" => $venta_data];
        }
        return ["success" => false, "message" => "Venta no encontrada"];
    }

    public function update($id, $cliente_id, $total, $estado) {
        // Validate input
        if(empty($cliente_id) || empty($total)) {
            return ["success" => false, "message" => "Cliente y total son requeridos"];
        }

        // Set venta properties
        $this->venta->id = $id;
        $this->venta->cliente_id = $cliente_id;
        $this->venta->total = $total;
        $this->venta->estado = $estado;

        // Update venta
        if($this->venta->update()) {
            return ["success" => true, "message" => "Venta actualizada exitosamente"];
        }
        return ["success" => false, "message" => "Error al actualizar venta"];
    }

    public function delete($id) {
        $this->venta->id = $id;
        if($this->venta->delete()) {
            return ["success" => true, "message" => "Venta eliminada exitosamente"];
        }
        return ["success" => false, "message" => "Error al eliminar venta"];
    }

    public function getStats($empresa_id = null, $periodo = 'mes') {
        return $this->venta->getStats($empresa_id, $periodo);
    }

    public function getVentasPorDia($empresa_id = null, $dias = 7) {
        $stmt = $this->venta->getVentasPorDia($empresa_id, $dias);
        $ventas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ventas[] = $row;
        }
        return ["success" => true, "data" => $ventas];
    }

    public function getProductosMasVendidos($empresa_id = null, $limite = 10) {
        $stmt = $this->venta->getProductosMasVendidos($empresa_id, $limite);
        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = $row;
        }
        return ["success" => true, "data" => $productos];
    }

    public function getAllVentas($empresa_id = null) {
        $stmt = $this->venta->read($empresa_id);
        $ventas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ventas[] = $row;
        }
        return $ventas;
    }
} 