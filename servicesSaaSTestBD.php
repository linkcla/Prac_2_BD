<!-- @Author: Blanca Atienzar Martinez (HTML, CSS y funcionalidad de SaaS) -->

<?php session_start() ;
include "conexion.php";
$conn = Conexion::getConnection();              

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createTest'])) {
                        // Procesar creación de nuevo test
                        $testName = $_POST['testName'];
                        $testDescription = $_POST['testDescription'];
                        $currentDate = date('Y-m-d H:i:s');

                        // Verificar si el test ya existe
                        $select_check_Query = "SELECT nom FROM TEST WHERE nom = '$testName'";
                        $result_test = mysqli_query($conn, $select_check_Query);

                        if(mysqli_num_rows($result_test) == 0) {
                            // Insertar el nuevo test
                            $insertQuery = "INSERT INTO TEST (nom, descripció, dataCreacio) VALUES ('$testName', '$testDescription', '$currentDate')";
                            
                            if(mysqli_query($conn, $insertQuery) == false) {
                                $message = "Error al crear el test.";
                                $_SESSION["error_msg"] = $message;
                                header("Location: ./servicesSaaSTestform.php");
                                die($message);
                            }
                            
                            // Persona que ha creado el test
                            $emailCreador = $_SESSION["email"];
                            $insert_query_saas = "INSERT INTO PERSONAL_REALITZA_TEST (emailP, nomT) VALUES ('$emailCreador', '$testName');";
                            $result_saas = mysqli_query($conn, $insert_query_saas);
                            if (!$result_saas) {
                                $msg = "Error al intentar crear el test, no se ha podido añadir la persona que lo ha creado";
                                $_SESSION["error_msg"] = $msg;
                                header("Location: ./servicesSaaSCreateform.php");
                                die($msg);
                            }

                            $message = "Test creat.";
                            $_SESSION["success_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }else {
                            $message = "Error. Test ja creat.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }
                    }

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteTest'])) {
                        
                        $testName = $_POST['noms'];                            

                        // Verificar si el test existe
                        $select_check_Query = "SELECT nom FROM TEST WHERE nom = '$testName'";
                        $result_test = mysqli_query($conn, $select_check_Query);

                        // Para Verificar si el test está en uso
                        $select_check_estat_Query = "SELECT nomT FROM ESTAT WHERE nomT = '$testName'";
                        $result_estat = mysqli_query($conn, $select_check_estat_Query);
                    
                        if(mysqli_num_rows($result_test) != 0 && mysqli_num_rows($result_estat) == 0) {
                            // Eliminar el  test
                            $deleteQuery = "DELETE FROM PERSONAL_REALITZA_TEST WHERE nomT = '$testName'";
                            if(mysqli_query($conn, $deleteQuery) == false) {
                                $message = "Error al eliminar el test.";
                                $_SESSION["error_msg"] = $message;
                                header("Location: ./servicesSaaSTestform.php");
                                die($message);
                            }

                            $deleteQuery = "DELETE FROM TEST WHERE nom = '$testName'";
                            if(mysqli_query($conn, $deleteQuery) == false) {
                                $message = "Error al eliminar el test.";
                                $_SESSION["error_msg"] = $message;
                                header("Location: ./servicesSaaSTestform.php");
                                die($message);
                            }

                            $message = "Test eliminado.";
                            $_SESSION["success_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }else if(mysqli_num_rows($result_estat) != 0){
                            $message = "Error. Test en uso.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }else {
                            $message = "Error. Test no eliminado.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }
                    }
                
            
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editEstat'])) {
                        
                        list($idConfig, $testNom) = explode('|', $_POST['selectedRow']);
                        $estatName = $_POST['nomsestats'];    
                       
                        $update_check_estat_Query = "UPDATE ESTAT SET estat = '$estatName' WHERE idConfigProducte = '$idConfig' AND nomT = '$testNom'";
                        if(mysqli_query($conn, $update_check_estat_Query) == false) {
                            $message = "Error al actualizar el estado.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        };
                        $message = "Estado actualizado.";
                        $_SESSION["success_msg"] = $message;
                        header("Location: ./servicesSaaSTestform.php");
                        die($message);
                    }

                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['elimarTestProd'])) {
                        
                        list($idConfig, $testNom) = explode('|', $_POST['selectedRow']);
                        $deleteQuery = "DELETE FROM ESTAT WHERE idConfigProducte = '$idConfig' AND nomT = '$testNom'";
                        if (!mysqli_query($conn, $deleteQuery)) {
                            $message = "Error al eliminar el producto de estat.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSViewform.php");
                            die($message);
                        }
                        $message = "Test eliminado del producto.";
                        $_SESSION["success_msg"] = $message;
                        header("Location: ./servicesSaaSTestform.php");
                        die($message);
                    }


                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['añadirEstadoTesT'])) {
                        // Procesar creación de nuevo test
                        $idConfig = $_POST['idConfigs'];
                        $testName = $_POST['noms']; 
                        
                        
                        // Verificar si el test ya esta añadido
                        $selectQueryEstat = "SELECT estat FROM ESTAT WHERE idConfigProducte='$idConfig' AND nomT='$testName';";
                        $result= mysqli_query($conn, $selectQueryEstat);
                        if(mysqli_num_rows($result) > 0) {
                            $message = "Error al añadir el test. El producto ya tiene el test asignado.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }

                        $insertQueryEstat = "INSERT INTO ESTAT (estat, nomT, idConfigProducte) VALUES ('Pendent', '$testName', '$idConfig');";
                        $result= mysqli_query($conn, $insertQueryEstat);
                        if(!$result) {
                            $message = "Error al añadir el test.";
                            $_SESSION["error_msg"] = $message;
                            header("Location: ./servicesSaaSTestform.php");
                            die($message);
                        }
                    
                        $message = "Test asignado correctamente.";
                        $_SESSION["success_msg"] = $message;
                        header("Location: ./servicesSaaSTestform.php");
                        die($message);
                    }
                ?>
                