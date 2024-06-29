<?php
class Viaje {
    private $codigoViaje;
    private $destino;
    private $costoViaje;
    private $cantidadMaxPasajeros;
    private $objEmpresa;
    private $objResponsable;
    private $mensajeOperacion;

    public function __construct() {
        $this->codigoViaje = 0;
        $this->destino = "";
        $this->costoViaje = 0.0;
        $this->cantidadMaxPasajeros = 0;
        $this->objEmpresa = new Empresa();
        $this->objResponsable = new Responsable();
        $this->mensajeOperacion = "";
    }

    // Getters y setters

    public function getIdViaje() {
        return $this->codigoViaje;
    }

    public function setCodigoViaje($codigoViaje) {
        $this->codigoViaje = $codigoViaje;
    }

    public function getDestino() {
        return $this->destino;
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function getCostoViaje() {
        return $this->costoViaje;
    }

    public function setCostoViaje($costoViaje) {
        $this->costoViaje = $costoViaje;
    }

    public function getCantidadMaxPasajeros() {
        return $this->cantidadMaxPasajeros;
    }

    public function setCantidadMaxPasajeros($cantidadMaxPasajeros) {
        $this->cantidadMaxPasajeros = $cantidadMaxPasajeros;
    }

    public function getEmpresa() {
        return $this->objEmpresa;
    }

    public function setEmpresa($idEmpresa) {
        $this->objEmpresa = $idEmpresa;
    }

    public function getResponsable() {
        return $this->objResponsable;
    }

    public function setResponsable($responsable) {
        $this->objResponsable = $responsable;
    }

    public function setMensajeOperacion($mensaje) {
        $this->mensajeOperacion = $mensaje;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function cargar($destino, $costoViaje, $cantidadMaxPasajeros, $objEmpresa, $objResponsable) {
        $this->setDestino($destino);
        $this->setCostoViaje($costoViaje);
        $this->setCantidadMaxPasajeros($cantidadMaxPasajeros);
        $this->setEmpresa($objEmpresa);
        $this->setResponsable($objResponsable);
    }
    public function Insertar() {
        $base = new BaseDatos();
        $respuesta = false;
        $idEmpre = $this->getEmpresa()->getIdempresa();
        $nroEmpleado = $this->getResponsable()->getNumeroEmpleado();
        $consulta = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('" . $this->getDestino() . "', " . $this->getCantidadMaxPasajeros() . ", " . $idEmpre . ", " . $nroEmpleado . ", " . $this->getCostoViaje() . ")";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $this->setCodigoViaje($base->getLastID());
                $respuesta = true;
            } else {
                $this->setMensajeOperacion("no se pudo ejecutar la consulta".$base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }
    public function Modificar() {
        $respuesta = false;
        $base = new BaseDatos();
        $nroEmpleado = $this->getResponsable()->getNumeroEmpleado();
        $consultaModificar = "UPDATE viaje SET 
            vdestino = '{$this->getDestino()}',
            vimporte = {$this->getCostoViaje()},
            rnumeroempleado = {$nroEmpleado},
            vcantmaxpasajeros = {$this->getCantidadMaxPasajeros()}
            WHERE idviaje = {$this->getIdViaje()}";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModificar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }
    public function Eliminar() {
        $base = new BaseDatos();
        $respuesta = false;
        $pasajeroEstandar = new PasajeroEstandar();
        $pasajeroVip = new PasajeroVip();
        $pasajeroNecesidades = new PasajeroNecesidades();
        $responsable = new Responsable();
        $nroEmplea = $this->getResponsable()->getNumeroEmpleado();
        $colEstandar = $pasajeroEstandar->listar($this->getIdViaje());
        $colVip = $pasajeroVip->listar($this->getIdViaje());
        $colNecesidades = $pasajeroNecesidades->listar($this->getIdViaje());
        if(count($colEstandar) > 0){
            foreach($colEstandar as $pasEsta){
                $pasEsta->eliminar();
            }
        }
        if(count($colVip) > 0){
            foreach($colVip as $pasVip){
                $pasVip->eliminar();
            }
        }
        if(count($colNecesidades) > 0){
            foreach($colNecesidades as $pasNes){
                $pasNes->eliminar();
            }
        }
        $consultaEliminar = "DELETE FROM viaje WHERE idviaje = {$this->getIdViaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEliminar)) {
                if($responsable->Buscar($nroEmplea)){
                    if($responsable->Eliminar()){
                        $respuesta = true;
                        $this->setMensajeOperacion("Viaje y pasajeros asociados eliminados correctamente.");
                    }else{
                        $this->setMensajeOperacion("no se elimino el responsable");
                    }
                }else{
                    $this->setMensajeOperacion("no se encontro al responsable");
                }                
            } else {
                $this->setMensajeOperacion("Error al eliminar el viaje.");
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }    
    
    public function Buscar($codigo) {
        $base = new BaseDatos();
        $consultaBuscar = "SELECT * FROM viaje WHERE idviaje = {$codigo}";
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaBuscar)) {
                if ($row = $base->Registro()) {
                    $empresa = new Empresa();
                    if($empresa->Buscar($row['idempresa'])){
                        $this->setCodigoViaje($row['idviaje']);
                        $responsable = new Responsable();
                        if ($responsable->Buscar($row['rnumeroempleado'])) {
                            $this->cargar($row['vdestino'], $row['vimporte'], $row['vcantmaxpasajeros'], $empresa, $responsable);
                            $respuesta = true;
                        } else {
                            $this->setMensajeOperacion("Error al buscar el responsable");
                        }
                       
                    }else{
                        $this->setMensajeOperacion("Error al Buscar el id de la empresa");
                    }
                } else {
                    $this->setMensajeOperacion("Viaje no encontrado");
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
    public static function listar($empresa) {
        $arregloViajes = [];
        $base = new BaseDatos();
        $idEmpresa  = $empresa->getIdempresa();
        $consultaViajes = "SELECT * FROM viaje WHERE idempresa = {$idEmpresa}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViajes)) {
                while ($row = $base->Registro()) {
                    $viaje = new Viaje();
                    if($viaje->Buscar($row['idviaje'])){
                        $arregloViajes[] = $viaje;
                    }
                }
            } else {
                $base->Cerrar();
            }
        } else {
            $base->Cerrar();
        }
        $base->Cerrar();
        return $arregloViajes;
    }
    public function __toString() {
        $cad = "";
        $cad .= "Código de Viaje: " . $this->getIdViaje() . "\nDestino: " . $this->getDestino() . "\nCosto del Viaje: " . $this->getCostoViaje() . "\nCantidad Máxima de Pasajeros: " . $this->getCantidadMaxPasajeros() . "\nResponsable:\n".$this->getResponsable()."\n" ;
        return $cad;
    }
}
?>

