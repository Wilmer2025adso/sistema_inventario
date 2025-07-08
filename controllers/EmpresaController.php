<?php
require_once 'models/Empresa.php';
require_once 'config/database.php';

class EmpresaController {
    private $db;
    private $empresa;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->empresa = new Empresa($this->db);
    }

    public function create($nombre, $ruc, $direccion, $telefono, $email, $logo = null) {
        // Validate input
        if(empty($nombre) || empty($ruc)) {
            return ["success" => false, "message" => "Nombre y RUC son requeridos"];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        // Check if RUC exists
        if($this->empresa->rucExists($ruc)) {
            return ["success" => false, "message" => "El RUC ya está registrado"];
        }

        // Set empresa properties
        $this->empresa->nombre = $nombre;
        $this->empresa->ruc = $ruc;
        $this->empresa->direccion = $direccion;
        $this->empresa->telefono = $telefono;
        $this->empresa->email = $email;
        $this->empresa->logo = $logo;
        $this->empresa->estado = 'activo';

        // Create empresa
        if($this->empresa->create()) {
            return ["success" => true, "message" => "Empresa registrada exitosamente"];
        }
        return ["success" => false, "message" => "Error al registrar empresa"];
    }

    public function read() {
        $stmt = $this->empresa->read();
        $empresas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empresas[] = $row;
        }
        return ["success" => true, "data" => $empresas];
    }

    public function readOne($id) {
        $this->empresa->id = $id;
        if($this->empresa->readOne()) {
            return [
                "success" => true,
                "data" => [
                    "id" => $id,
                    "nombre" => $this->empresa->nombre,
                    "ruc" => $this->empresa->ruc,
                    "direccion" => $this->empresa->direccion,
                    "telefono" => $this->empresa->telefono,
                    "email" => $this->empresa->email,
                    "logo" => $this->empresa->logo,
                    "estado" => $this->empresa->estado,
                    "created" => $this->empresa->created,
                    "updated" => $this->empresa->updated
                ]
            ];
        }
        return ["success" => false, "message" => "Empresa no encontrada"];
    }

    public function update($id, $nombre, $ruc, $direccion, $telefono, $email, $logo = null, $estado = 'activo') {
        // Validate input
        if(empty($nombre) || empty($ruc)) {
            return ["success" => false, "message" => "Nombre y RUC son requeridos"];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Email inválido"];
        }

        // Check if RUC exists (excluding current empresa)
        if($this->empresa->rucExists($ruc, $id)) {
            return ["success" => false, "message" => "El RUC ya está registrado por otra empresa"];
        }

        // Set empresa properties
        $this->empresa->id = $id;
        $this->empresa->nombre = $nombre;
        $this->empresa->ruc = $ruc;
        $this->empresa->direccion = $direccion;
        $this->empresa->telefono = $telefono;
        $this->empresa->email = $email;
        $this->empresa->logo = $logo;
        $this->empresa->estado = $estado;

        // Update empresa
        if($this->empresa->update()) {
            return ["success" => true, "message" => "Empresa actualizada exitosamente"];
        }
        return ["success" => false, "message" => "Error al actualizar empresa"];
    }

    public function delete($id) {
        $this->empresa->id = $id;
        if($this->empresa->delete()) {
            return ["success" => true, "message" => "Empresa eliminada exitosamente"];
        }
        return ["success" => false, "message" => "Error al eliminar empresa"];
    }

    public function getStats($empresa_id = null) {
        if($empresa_id) {
            $this->empresa->id = $empresa_id;
            return $this->empresa->getStats();
        }
        return ["success" => false, "message" => "ID de empresa requerido"];
    }

    public function getAllEmpresas() {
        $stmt = $this->empresa->read();
        $empresas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empresas[] = $row;
        }
        return $empresas;
    }
} 