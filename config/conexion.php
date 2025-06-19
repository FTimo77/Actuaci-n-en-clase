<?php
class Conexion {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $database = 'tienda_online';

    function conectar() {
        $conexion = new mysqli($this->host, $this->user, $this->pass, $this->database);

        if ($conexion->connect_error) {
            die('Failed conection'. $conexion->connect_error);
        }

        return $conexion;
    }
}
?>