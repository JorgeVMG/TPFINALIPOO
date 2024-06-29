<?php
include_once "../baseDatos/baseDatos.php";
class PasajeroEstandar extends Pasajero{
    public function __construct(){
        parent::__construct();
    }
    public function __toString(){
        $cad = "Pasajero Estandar: \n";
        $cad .= parent::__toString();
        return $cad;
    }
    public function Buscar($documento) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajeroestandar WHERE pdocumento = '{$documento}'";
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()){
                    if(parent::Buscar($row2['pdocumento'])){
                        $respuesta = true;
                    }
                } else {
                    $this->setMensajeOperacion("No se encontró el pasajero.");
                }
            } else {
                $this->setMensajeOperacion("ERROR: al ejecutar".$base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
            $consultaBorraEstandar = "DELETE FROM pasajeroestandar WHERE pdocumento=" . $this->getNroDocumento();
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaBorraEstandar)) {
                    if (parent::eliminar()) {
                        $resp = true;
                    } else {
                        $this->setMensajeOperacion("Error al eliminar el pasajero estandar de la tabla pasajero.\n");
                    }
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
                $base->Cerrar();
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        return $resp;
    }
    public function insertar() {
        $base = new BaseDatos();
        $respuesta = false;
        if (parent::insertar()) {
            $consultaInsertarEstandar = "INSERT INTO pasajeroestandar (pdocumento) VALUES ('" . $this->getNroDocumento() . "')";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaInsertarEstandar)) {
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
                $base->Cerrar();
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion("Error al insertar en la tabla pasajero.");
        }
        
        return $respuesta;
    }
    public static function listar($idViaje) {
        $arregloPasajeros = [];
        $base = new BaseDatos();
        $consultaPasajeros = "SELECT pe.* FROM pasajeroestandar pe INNER JOIN pasajero p ON pe.pdocumento = p.pdocumento WHERE p.idviaje = {$idViaje}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                while ($row = $base->Registro()) {
                    $pasajero = new PasajeroEstandar();
                    if($pasajero->Buscar($row['pdocumento'])){
                        $arregloPasajeros[] = $pasajero;
                    }
                }
            } else {
                $base->Cerrar();
            }
        } else {
            $base->Cerrar();
        }
        $base->Cerrar();
        return $arregloPasajeros;
    }
}