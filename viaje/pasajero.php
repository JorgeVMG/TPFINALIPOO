<?php
include_once "../baseDatos/baseDatos.php";
include_once "viaje.php";
class Pasajero {
    private $nombre;
    private $apellido;
    private $nroDocumento;
    private $telefono;
    private $mensajeOperacion;
    private $viaje;// tiene que tener obj viaje 
    public function __construct() {
        $this->nombre = "";
        $this->apellido = "";
        $this->nroDocumento = 0;
        $this->telefono = 0;
        $this->mensajeOperacion = "";
        $this->viaje = new Viaje;
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nom) {
        $this->nombre = $nom;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function setApellido($apel) {
        $this->apellido = $apel;
    }
    public function getNroDocumento() {
        return $this->nroDocumento;
    }

    public function setNroDocumento($nroDoc) {
        $this->nroDocumento = $nroDoc;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($tel) {
        $this->telefono = $tel;
    }
    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensaje) {
        $this->mensajeOperacion = $mensaje;
    }
    public function getViaje(){
        return $this->viaje;
    }
    public function setIdViaje($idViaje){
        $this->viaje = $idViaje;
    }
    public function cargar($nom, $apel, $nroDoc, $tel,$objVia) {
        $this->setNombre($nom);
        $this->setApellido($apel);
        $this->setNroDocumento($nroDoc);
        $this->setTelefono($tel);
        $this->setIdViaje($objVia);
    }
    public function Buscar($documento) {
        $base = new BaseDatos();
        $viaje = new Viaje();
        $consulta = "SELECT * FROM pasajero WHERE pdocumento = '{$documento}'"; 
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    if($viaje->Buscar($row['idviaje'])){
                        $this->cargar($row['pnombre'],$row['papellido'],$row['pdocumento'],$row['ptelefono'],$viaje);
                        $respuesta = true;
                    }
                } else {
                    $this->setMensajeOperacion("No se encontró el pasajero.");
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }
    
    public function modificar() {
        $resp = false; 
        $base = new BaseDatos();
        $idViaje = $this->getViaje()->getIdViaje();
        $consultaModifica = "UPDATE pasajero SET pnombre='" . $this->getNombre() ."', papellido='" . $this->getApellido() . "', ptelefono='" . $this->getTelefono() . "', idviaje='" . $idViaje . "'WHERE pdocumento='" . $this->getNroDocumento() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
            $base->Cerrar(); 
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        
        return $resp;
    }
    
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaBorra = "DELETE FROM pasajero WHERE pdocumento=" . $this->getNroDocumento();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("no se elimino al pasajero". $base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion("no se pudo iniciar la base de datos". $base->getError());
        }
        return $resp;
    }
    
    public function Insertar() {
        $base = new BaseDatos();
        $respuesta = false;
        $idViaje = $this->getViaje()->getIdViaje();
        $consulta = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES ('" . $this->getNroDocumento() . "', '" . $this->getNombre() . "', '" . $this->getApellido() . "', '" . $this->getTelefono() . "', " . $idViaje . ")";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($base->getError());
                echo $this->getMensajeOperacion();
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }
    
        return $respuesta;
    }
    public static function listar($idViaje) {
        $arregloPasajeros = [];
        $base = new BaseDatos();
        $consultaPasajeros = "SELECT * FROM pasajero WHERE idviaje = {$idViaje} ORDER BY papellido";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                while ($row = $base->Registro()) {
                    $pasajero = new Pasajero();
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

    public function __toString() {
        $cad = "";
        $cad.= "Nombre: " . $this->getNombre() . "\nApellido: " . $this->getApellido() . "\nNúmero de Documento: " . $this->getNroDocumento() . "\nTeléfono: " . $this->getTelefono() . "\n" ;
        return $cad;
    }
}
?>

