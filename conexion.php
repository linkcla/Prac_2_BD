<!--
------------- PARA PODER USAR LA CONEXIÓN DESDE OTRA PARTE DEL CODIGO -------------
require_once 'conexion.php';

$conexio = Conexion::getInstance('localhost', 'root', '', 'ex_php1');
$conn = $conexio->getConnection();
-->

<?php
class Conexion {
    private static $instance = null;
    private $connection;

    private function __construct($host, $user, $password, $database) {
        $this->connection = new mysqli($host, $user, $password, $database);
        if ($this->connection->connect_error) {
            die("Error de conexió: " . $this->connection->connect_error);
        }
    }

    public static function getInstance($host, $user, $password, $database) {
        if (self::$instance == null) {
            self::$instance = new Conexion($host, $user, $password, $database);
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    } 
}
$conexio = Conexion::getInstance('localhost', 'root', '', 'bd_grupo');
?>