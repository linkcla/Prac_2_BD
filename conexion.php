<!-- Author: Hai Zi Bibiloni Trobat -->
<?php
class Conexion {
    public static function getConnection() {
        $conn = new mysqli('localhost', 'root', '', 'bd_grupo');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>