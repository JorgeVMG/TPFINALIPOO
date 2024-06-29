<?php
include_once "../baseDatos/BaseDatos.php";

class Responsable {
    private $numeroEmpleado;
    private $numeroLicencia;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    public function __construct() {
        $this->numeroEmpleado = 0;
        $this->numeroLicencia = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->mensajeOperacion = "";
    }

    // Getters y setters

    public function getNumeroEmpleado(){
        return $this->numeroEmpleado;
    }
    public function setNumeroEmpleado($numEmpleado){
        $this->numeroEmpleado = $numEmpleado;
    }

    public function getNumeroLicencia() {
        return $this->numeroLicencia;
    }

    public function setNumeroLicencia($numeroLicencia) {
        $this->numeroLicencia = $numeroLicencia;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setMensajeOperacion($mensaje) {
        $this->mensajeOperacion = $mensaje;
    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

    public function cargar($numeroLicencia, $nombre, $apellido) {
        $this->setNumeroLicencia($numeroLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    public function Insertar() {
        $base = new BaseDatos();
        $respuesta = false;
        $consulta = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES (" . $this->getNumeroLicencia() . ", '" . $this->getNombre() . "', '" . $this->getApellido() . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $this->numeroEmpleado = $base->getLastID();
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
    

    public function modificar() {
        $resp = false; 
        $base = new BaseDatos();
        $consultaModifica = "UPDATE responsable SET rnombre = '".$this->getNombre()."', rapellido = '".$this->getApellido()."' WHERE rnumeroEmpleado = ".$this->getNumeroEmpleado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                echo "no se ejecuto";
                $this->setMensajeOperacion($base->getError());
            }
            $base->Cerrar();
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }
    public function Eliminar() {
        $base = new BaseDatos();
        $consulta = "DELETE FROM responsable WHERE rnumeroEmpleado = {$this->getNumeroEmpleado()}";
        $respuesta = false;
        if($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }else {
            $this->setmensajeoperacion($base->getError());
        }
        return $respuesta;
    }
    public function Buscar($numeroEmpleado) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado = {$numeroEmpleado}";
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()){
                    $this->setNumeroEmpleado($row['rnumeroempleado']);
                    $this->setNumeroLicencia($row['rnumerolicencia']);
                    $this->setNombre($row['rnombre']);
                    $this->setApellido($row['rapellido']);
                    $respuesta = true;
                } else {
                    $this->setmensajeoperacion("No se encontró el responsable.");
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $respuesta;
    }

    public static function listar($condicion = "") {
        $arregloResponsable = [];
        $base = new BaseDatos();
        $consulta="SELECT * FROM responsable ";
		if ($condicion!=""){
		    $consulta=$consulta.' WHERE'.$condicion;
		}
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $resp = new Responsable();
                    if($resp->Buscar($row['rnumeroempleado'])){
                        $arregloResponsable[] = $resp;
                    }
                }
            } else {
                $base->Cerrar();
            }
        } else {
            $base->Cerrar();
        }
        $base->Cerrar();
        return $arregloResponsable;
    }

    public function __toString(){
        $cad = "";
        $cad.= "Numero de empleado: ".$this->getNumeroEmpleado().
            "\nNumero Licencia:".$this->getNumeroLicencia().
            "\nNombre y Apellido  ".$this->getNombre()." ".$this->getApellido()."\n";
        return $cad;
    }
}

?>
