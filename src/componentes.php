<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->
<?php
require_once 'conexio.php';

class Componentes {

    public static function crearTestSaas() {
        $conn = Conexion::getConnection();

    }

    public static function eliminarTestSaas() {
        $conn = Conexion::getConnection();
    }
}
?>