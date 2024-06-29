<?php
class PasajeroNecesidades extends Pasajero{
    private $necesidades;
    
    public function __construct(){
        parent:: __construct();
        $this->necesidades = "";
    }
    public function getNecesidades(){
        return $this->necesidades;
    }
    public function setNecesidades($neces){
        $this->necesidades = $neces;
    }
    public function cargarNecesidades($nom, $apel, $nroDoc, $tel,$objVia,$neces){
        parent::cargar($nom, $apel, $nroDoc, $tel,$objVia);
        $this->setNecesidades($neces);
    }
    public function Buscar($documento) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajeronecesidades WHERE pdocumento = '{$documento}'"; 
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()){
                    if(parent::Buscar($row2['pdocumento'])){
                        $this->setNecesidades($row2['necesidades_especiales']);
                        $respuesta = true;
                    }else{
                        $this->setMensajeOperacion("No se encontro en el pasajero".$base->getError());
                    }
                } else {
                    $this->setMensajeOperacion("No se encontró el pasajero en la tabla pasajero.");
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
            $consultaModifica = "UPDATE pasajeronecesidades SET necesidades_especiales = '".$this->getNecesidades()."' WHERE pdocumento=" . $this->getNroDocumento();

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
            $consultaBorraVip = "DELETE FROM pasajeronecesidades WHERE pdocumento=" . $this->getNroDocumento(); 
            if ($base->Ejecutar($consultaBorraVip)) {
                if(parent::eliminar()){
                    $resp = true;
                }else {
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
   
    public function Insertar() {
        $base = new BaseDatos();
        $resp = false;
        if(parent::Insertar()) {
            $consulta = "INSERT INTO pasajeronecesidades (pdocumento, necesidades_especiales) VALUES ('".$this->getNroDocumento()."', '".$this->necesidades."')";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consulta)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion("Error al ingresar necesidades".$base->getError());
                }
                $base->Cerrar(); 
            } else {
                $this->setMensajeOperacion("Error al ingresar a la base de datos".$base->getError());
                $base->Cerrar();
            }
        } else {
            $this->setMensajeOperacion($this->getMensajeOperacion());
            $base->Cerrar();
        }
        $base->Cerrar();
    
        return $resp;
    }
    public static function listar($idViaje) {
        $arregloPasajeros = [];
        $base = new BaseDatos();
        $consultaPasajeros = "SELECT pn.* FROM pasajeronecesidades pn INNER JOIN pasajero p ON pn.pdocumento = p.pdocumento WHERE p.idviaje = {$idViaje}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                while ($row = $base->Registro()) {
                    $pasajero = new PasajeroNecesidades();
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
        $cad = "Pasajero con Necesidades: \n";
        $cad .= parent::__toString();
        $cad .= "Necesidades del Pasajero: ".$this->getNecesidades();
        return $cad;
    }
}