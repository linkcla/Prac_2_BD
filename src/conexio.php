<!-- @Author: Hai Zi Bibiloni Trobat -->

<?php
class Conexion {
    
    // Patron de singleton para una unica instancia
    public static function getConnection() {
        $conn = new mysqli('localhost', 'root', '', 'bd_grupo');

        // Si falla devolver el error
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        
        return $conn;
    }
}
?>