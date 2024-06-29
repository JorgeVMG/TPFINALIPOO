<?php
class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeOperacion;

    public function __construct() {
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
        $this->mensajeOperacion = "";
    }

    public function getIdempresa() {
        return $this->idempresa;
    }
    public function setIdempresa($idempresa){
        $this->idempresa = $idempresa;
    }
    public function getNombre() {
        return $this->enombre;
    }

    public function setNombre($enombre) {
        $this->enombre = $enombre;
    }

    public function getDireccion() {
        return $this->edireccion;
    }

    public function setDireccion($edireccion) {
        $this->edireccion = $edireccion;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    public function cargar($enombre, $edireccion) {
        $this->setNombre($enombre);
        $this->setDireccion($edireccion);
    }
    public function Insertar() {
        $base = new BaseDatos();
        $respuesta = false;
        $consulta = "INSERT INTO empresa (enombre, edireccion) VALUES ('" . $this->getNombre() . "', '" . $this->getDireccion() . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $this->idempresa = $base->getLastID();
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }

        return $respuesta;
    }
    public function Modificar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaModifica = "UPDATE empresa SET enombre='" . $this->getNombre() . "', edireccion='" . $this->getDireccion() . "' WHERE idempresa=" . $this->getIdempresa();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function Eliminar() {
        $base = new BaseDatos();
        $viaje = new Viaje();
        $resp = false;
        $empresa = new Empresa();
        $empresa->Buscar($this->getIdempresa());
        $colViajes = $viaje->listar($empresa);
        if(count($colViajes)>0){
            foreach($colViajes as $viaj){
                $viaj->Eliminar();
            }
        }
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM empresa WHERE idempresa=" . $this->getIdempresa();
            if ($base->Ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function Buscar($id) {
        $base = new BaseDatos();
        $consultaBuscar = "SELECT * FROM empresa WHERE idempresa=" . $id;
        $encontro = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBuscar)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdempresa($id);
                    $this->setNombre($row2['enombre']);
                    $this->setDireccion($row2['edireccion']);
                    $encontro = true;
                } else {
                    $this->setmensajeoperacion("Empresa no encontrada");
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $encontro;
    }
    public static function listar($condicion = "") {
        $arregloEmpresa = [];
        $base = new BaseDatos();
        $consulta="SELECT * FROM empresa";
		if ($condicion!=""){
		    $consulta=$consulta.' WHERE'.$condicion;
		}
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $empresa = new Empresa();
                    if($empresa->Buscar($row['idempresa'])){
                        $arregloEmpresa[] = $empresa;
                    }
                }
            } else {
                $base->Cerrar();
            }
        } else {
            $base->Cerrar();
        }
        $base->Cerrar();
        return $arregloEmpresa;
    }
    public function __toString() {
        return "ID Empresa: " . $this->getIdempresa() . "\nNombre: " . $this->getNombre() . "\nDirección: " . $this->getDireccion() . "\n";
    }
}