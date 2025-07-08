<?php
require_once 'models/Cliente.php';
require_once 'config/database.php';

class ClienteController {
    private $db;
    private $cliente;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cliente = new Cliente($this->db);
    }

    public function create($empresa_id, $nombre, $email, $telefono, $direccion, $tipo, $documento) {
        // Validate input
        if(empty($nombre) || empty($empresa_id)) {
            return ["success" => false, "message" => "Nombre y empresa son requeridos"];
        }

        if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        // Set cliente properties
        $this->cliente->empresa_id = $empresa_id;
        $this->cliente->nombre = $nombre;
        $this->cliente->email = $email;
        $this->cliente->telefono = $telefono;
        $this->cliente->direccion = $direccion;
        $this->cliente->tipo = $tipo;
        $this->cliente->documento = $documento;
        $this->cliente->estado = 'activo';

        // Create cliente
        if($this->cliente->create()) {
            return ["success" => true, "message" => "Cliente registrado exitosamente"];
        }
        return ["success" => false, "message" => "Error al registrar cliente"];
    }

    public function read($empresa_id = null) {
        $stmt = $this->cliente->read($empresa_id);
        $clientes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $row;
        }
        return ["success" => true, "data" => $clientes];
    }

    public function readOne($id) {
        $this->cliente->id = $id;
        if($this->cliente->readOne()) {
            return [
                "success" => true,
                "data" => [
                    "id" => $id,
                    "empresa_id" => $this->cliente->empresa_id,
                    "nombre" => $this->cliente->nombre,
                    "email" => $this->cliente->email,
                    "telefono" => $this->cliente->telefono,
                    "direccion" => $this->cliente->direccion,
                    "tipo" => $this->cliente->tipo,
                    "documento" => $this->cliente->documento,
                    "estado" => $this->cliente->estado,
                    "created" => $this->cliente->created,
                    "updated" => $this->cliente->updated
                ]
            ];
        }
        return ["success" => false, "message" => "Cliente no encontrado"];
    }

    public function update($id, $nombre, $email, $telefono, $direccion, $tipo, $documento, $estado = 'activo') {
        // Validate input
        if(empty($nombre)) {
            return ["success" => false, "message" => "Nombre es requerido"];
        }

        if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        // Set cliente properties
        $this->cliente->id = $id;
        $this->cliente->nombre = $nombre;
        $this->cliente->email = $email;
        $this->cliente->telefono = $telefono;
        $this->cliente->direccion = $direccion;
        $this->cliente->tipo = $tipo;
        $this->cliente->documento = $documento;
        $this->cliente->estado = $estado;

        // Update cliente
        if($this->cliente->update()) {
            return ["success" => true, "message" => "Cliente actualizado exitosamente"];
        }
        return ["success" => false, "message" => "Error al actualizar cliente"];
    }

    public function delete($id) {
        $this->cliente->id = $id;
        if($this->cliente->delete()) {
            return ["success" => true, "message" => "Cliente eliminado exitosamente"];
        }
        return ["success" => false, "message" => "Error al eliminar cliente"];
    }

    public function search($search_term, $empresa_id = null) {
        $stmt = $this->cliente->search($search_term, $empresa_id);
        $clientes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $row;
        }
        return ["success" => true, "data" => $clientes];
    }

    public function getStats($empresa_id = null) {
        return $this->cliente->getStats($empresa_id);
    }

    public function getAllClientes($empresa_id = null) {
        $stmt = $this->cliente->read($empresa_id);
        $clientes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $row;
        }
        return $clientes;
    }
} 