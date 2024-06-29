<?php
class BaseDatos{
    private $HOSTNAME;
    private $BASEDATOS;
    private $USUARIO;
    private $CLAVE;
    private $CONEXION;
    private $QUERY;
    private $RESULT;
    private $ERROR;
    
    public function __construct(){
        $this->HOSTNAME = "127.0.0.1";
        $this->BASEDATOS = "bdviajes";
        $this->USUARIO = "root";
        $this->CLAVE="";
        $this->RESULT=0;
        $this->QUERY="";
        $this->ERROR="";
    }
    public function getHostName() {
        return $this->HOSTNAME;
    }
    public function setHostName($hostname) {
        $this->HOSTNAME = $hostname;
    }
    public function getBaseDatos() {
        return $this->BASEDATOS;
    }
    public function setBaseDatos($basedatos) {
        $this->BASEDATOS = $basedatos;
    }
    public function getUsuario() {
        return $this->USUARIO;
    }
    public function setUsuario($usuario) {
        $this->USUARIO = $usuario;
    }
    public function getClave() {
        return $this->CLAVE;
    }
    public function setClave($clave) {
        $this->CLAVE = $clave;
    }
    public function getConexion() {
        return $this->CONEXION;
    }
    public function setConexion($conexion) {
        $this->CONEXION = $conexion;
    }
    public function getQuery() {
        return $this->QUERY;
    }
    public function setQuery($query) {
        $this->QUERY = $query;
    }
    public function getResult() {
        return $this->RESULT;
    }
    public function setResult($result) {
        $this->RESULT = $result;
    }
    public function getError() {
        return $this->ERROR;
    }
    public function setError($error) {
        $this->ERROR = $error;
    }
    public function Iniciar(){
        $respuesta = false;
        $conexion = mysqli_connect($this->getHostName(),$this->getUsuario(),$this->getClave(),$this->getBaseDatos());
        if($conexion){
            if(mysqli_select_db($conexion,$this->getBaseDatos())){
                $this->setConexion($conexion);
                unset($this->QUERY);
                unset($this->ERROR);
                $respuesta = true;
            }
        }
        if($respuesta == false){
            $this->ERROR = mysqli_errno($conexion) . ": " .mysqli_error($conexion);
        }
        return $respuesta;
    }
    public function Ejecutar($consulta){
        $respuesta = false;
        unset($this->ERROR);
        $this->setQuery($consulta);
        if(  $this->RESULT = mysqli_query( $this->getConexion(),$consulta)){
            $respuesta = true;
        } else {
            $this->ERROR =mysqli_errno( $this->CONEXION).": ". mysqli_error( $this->CONEXION);
        }
        return $respuesta;
    }
    public function Cerrar() {
        if ($this->CONEXION !== null) {
            mysqli_close($this->CONEXION);
            $this->CONEXION = null; // Opcionalmente, podrías limpiar la propiedad de conexión
        }
    }
    public function getLastID() {
        // Retorna el último ID autogenerado por la última consulta INSERT ejecutada
        return mysqli_insert_id($this->CONEXION);
    }
    public function Registro() {
        $resp = null;
        if ($this->RESULT){
            unset($this->ERROR);
            if($temp = mysqli_fetch_assoc($this->RESULT)){
                $resp = $temp;
            }else{
                mysqli_free_result($this->RESULT);
            }
        }else{
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        return $resp ;
    }
}