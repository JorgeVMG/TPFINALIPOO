<?php
class PasajeroVip extends Pasajero{
    private $nroFrecuencia;
    private $millas;
    
    public function __construct(){
        parent:: __construct();
        $this->nroFrecuencia = 0;
        $this->millas = 0;
    }
    public function getNroFrecuencia(){
        return $this->nroFrecuencia;
    }
    public function setNroFrecuencia($nroFrec){
        $this->nroFrecuencia = $nroFrec;
    }
    public function getMillas(){
        return $this->millas;
    }
    public function setMillas($mil){
        $this->millas = $mil;
    }
    public function cargarVip($nom, $apel, $nroDoc, $tel, $objVia, $nroFrec, $mil) {
        parent::cargar($nom, $apel, $nroDoc, $tel, $objVia);
        $this->setNroFrecuencia($nroFrec);
        $this->setMillas($mil);
    }
    public function Buscar($documento) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajerovip WHERE pdocumento = '{$documento}'"; 
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()){
                    if(parent::Buscar($row2['pdocumento'])){
                        $this->setNroFrecuencia($row2['numero_viajes']);
                        $this->setMillas($row2['millas_acumuladas']);
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
        if(parent::modificar()){
            $consultaModifica = "UPDATE pasajerovip SET numero_viajes='".$this->getNroFrecuencia().
            "', millas_acumuladas='". $this->getMillas()."' WHERE pdocumento=" . $this->getNroDocumento();
            if($base->Iniciar()){
	            if($base->Ejecutar($consultaModifica)){
	                $resp=  true;
	            }else{
	                $this->setmensajeoperacion($base->getError());
                    $base->Cerrar(); 
                } 
            }else{
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
    
        if ($base->Iniciar()) {
            $consultaBorraVip = "DELETE FROM pasajerovip WHERE pdocumento=" . $this->getNroDocumento(); 
            if ($base->Ejecutar($consultaBorraVip)) {
                if(parent::eliminar()){
                    $resp = true;
                }else {
                    $this->setMensajeOperacion("Error al eliminar el pasajero estandar de la tabla pasajero.\n");
                }
            } else {
                $this->setMensajeOperacion("Error al eliminar el pasajero VIP: " . $base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion("Error al iniciar la base de datos: " . $base->getError());
        }
    
        return $resp;
    }
    public function Insertar() {
        $base = new BaseDatos();
        $resp = false;
        if (parent::Insertar()) {
            $consulta = "INSERT INTO pasajerovip (pdocumento, numero_viajes, millas_acumuladas) VALUES ('" . $this->getNroDocumento() . "', " . $this->getNroFrecuencia() . ", " . $this->getMillas() . ")";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consulta)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
                $base->Cerrar(); 
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($this->getMensajeOperacion());
        }
    
        return $resp;
    }
    public static function listar($idViaje) {
        $arregloPasajeros = [];
        $base = new BaseDatos();
        $consultaPasajeros = "SELECT pv.* FROM pasajerovip pv INNER JOIN pasajero p ON pv.pdocumento = p.pdocumento WHERE p.idviaje = {$idViaje}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                while ($row = $base->Registro()) {
                    $pasajero = new PasajeroVip();
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
    public function __toString(){
        $cad = "Pasajero VIP: \n";
        $cad .= parent::__toString();
        $cad .= "Numero de Viajes de Frecuencia: ".$this->getNroFrecuencia()."\nMillas recorridas: ".$this->getMillas();
        return $cad;
    }
}
