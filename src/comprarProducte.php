<!-- @Author: Hai Zi Bibiloni Trobat -->
<?php
require_once 'conexio.php';

class Usuari {
    public static function comprarP($ipType, $ipValue, $tipusRAM, $gbRAM, $tipusDD, $gbDD, $modelCPU, $nNuclis, $nomSO, $emailU, $nomOrg, $estat, $mesos, $dataInici){
        $conn = Conexion::getConnection();

        // Per establir els valors de IPv4 e IPv6 segons la selección de l'usuari
        if ($ipType == 'IPv4') {
            $iPv4 = $ipValue;
            $iPv6 = NULL;
        } else {
            $iPv4 = NULL;
            $iPv6 = $ipValue;
        }

        // Comprovar si la combinació de tipusDD i GBDD existeix a la taula DISC_DUR
        $sql = "SELECT * FROM DISC_DUR WHERE tipus = '$tipusDD' AND GB = $gbDD";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_msg'] = "La combinació de tipusDD i GBDD no està disponible.";
            return false;
        }

        // Comprovar si la combinació de tipusRAM i GBRAM existeix a la taula RAM
        $sql = "SELECT * FROM RAM WHERE tipus = '$tipusRAM' AND GB = $gbRAM";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_msg'] = "La combinació de tipusRAM i GBRAM no està disponible.";
            return false;
        }

        // Comprovar si la combinació de modelCPU i nNuclis existeix a la taula CPU
        $sql = "SELECT * FROM CPU WHERE model = '$modelCPU' AND nNuclis = '$nNuclis'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_msg'] = "La combinació de modelCPU i nNuclis no està disponible.";
            return false;
        }

        // Comprovar si la IP seleccionada existeix a la base de dades
        $sql = "SELECT * FROM PAAS WHERE (iPv4 = '$iPv4' OR iPv6 = '$iPv6')";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_msg'] = "La IP seleccionada no està disponible.";
            return false;
        }

        // Comprovar si el producte ja existeix a la base de dades
        $sql = "SELECT idConfig FROM PAAS WHERE tipusRAM = '$tipusRAM' AND gbRAM = $gbRAM AND tipusDD = '$tipusDD' AND gbDD = $gbDD AND modelCPU = '$modelCPU' AND nNuclis = '$nNuclis' AND nomSO = '$nomSO' AND (iPv4 = '$iPv4' OR iPv6 = '$iPv6')";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $idConfig = $row['idConfig'];

            // Comprovar si el idConfigProducte té l'estat de "Aprovat" 
            $sql = "SELECT * FROM ESTAT WHERE idConfigProducte = $idConfig AND estat = 'Aprovat'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Comprovar si ja existeix un contracte amb el mateix idConfigProducte
                $sql = "SELECT * FROM CONTRACTE WHERE idConfigProducte = $idConfig";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $_SESSION['error_msg'] = "Ja hi ha un contracte amb el mateix idConfigProducte.";
                    return false;
                } else {
                    // Insertar un el contracte a la taula CONTRACTE
                    $sql = "INSERT INTO CONTRACTE (dataInici, estat, nom, emailU, idConfigProducte, mesos, domini) VALUES ('$dataInici', '$estat', '$nomOrg', '$emailU', $idConfig, $mesos, 'NULL')";
                    mysqli_query($conn, $sql);

                    $_SESSION['success_msg'] = "El contracte s'ha creat correctament.";
                    return true;
                }
            } else {
                $_SESSION['error_msg'] = "El producte no té l'estat de 'Aprovat'.";
                return false;
            }
        } else {
            $_SESSION['error_msg'] = "El producte no existeix.";
            return false;
        }

        return true;
    }

    public static function comprarS($tipusMCMS, $tipusCDN, $tipusSSL, $tipusSGBD, $tipusRAM, $gbRAM, $tipusDD, $gbDD, $domini, $emailU, $nomOrg, $estat, $mesos, $dataInici){
        $conn = Conexion::getConnection();

        // Comprovar si la combinació de tipusDD i GBDD existeix a la taula DISC_DUR
        $sql = "SELECT * FROM DISC_DUR WHERE tipus = '$tipusDD' AND GB = $gbDD";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_msg'] = "La combinació de tipusDD i GBDD no està disponible.";
            return false;
        }

        // Comprovar si la combinació de tipusRAM i GBRAM existeix a la taula RAM
        $sql = "SELECT * FROM RAM WHERE tipus = '$tipusRAM' AND GB = $gbRAM";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_msg'] = "La combinació de tipusRAM i GBRAM no està disponible.";
            return false;
        }

        // Comprovar si el producte ja existeix a la base de dades
        $sql = "SELECT idConfig FROM SAAS WHERE tipusMCMS = '$tipusMCMS' AND tipusCDN = '$tipusCDN' AND tipusSSL = '$tipusSSL' AND tipusSGBD = '$tipusSGBD' AND tipusRAM = '$tipusRAM' AND gbRAM = $gbRAM AND tipusDD = '$tipusDD' AND gbDD = $gbDD";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $idConfig = $row['idConfig'];

            // Comprovar si el idConfigProducte té l'estat de "Aprovat" 
            $sql = "SELECT estat FROM ESTAT WHERE idConfigProducte = $idConfig";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $estatProducte = $row['estat'];

                if ($estatProducte == 'Aprovat') {
                    // Comprovar si ja existeix un contracte amb les mateixes característiques i el mateix domini
                    $sql = "SELECT * FROM CONTRACTE WHERE domini = '$domini'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $_SESSION['error_msg'] = "Ja hi ha un contracte amb el mateix domini.";
                        return false;
                    } else {
                        // Insertar un el contracte a la taula CONTRACTE
                        $sql = "INSERT INTO CONTRACTE (dataInici, estat, nom, emailU, idConfigProducte, mesos, domini) VALUES ('$dataInici', '$estat', '$nomOrg', '$emailU', $idConfig, $mesos, '$domini')";
                        mysqli_query($conn, $sql);

                        $_SESSION['success_msg'] = "El contracte s'ha creat correctament.";
                        return true;
                    }
                } else {
                    $_SESSION['error_msg'] = "El producte no té l'estat de 'Aprovat'.";
                    return false;
                }
            } else {
                $_SESSION['error_msg'] = "No s'ha pogut obtenir l'estat del producte.";
                return false;
            }
        } else {
            $_SESSION['error_msg'] = "El producte no existeix.";
            return false;
        }

        return true;
    }

}
?>