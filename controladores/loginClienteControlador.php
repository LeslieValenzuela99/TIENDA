<?php
// loginClienteControlador.php

if ($peticion_ajax) {
    require_once "../modelos/mainModel.php";
} else {
    require_once "./modelos/mainModel.php";
}

class loginClienteControlador extends mainModel
{

    /*---------- Controlador iniciar sesion cliente - Controller login client ----------*/
    public function iniciar_sesion_cliente_controlador()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['login_email']) || !isset($_POST['login_clave'])) {
                // Manejo del error de campos faltantes
                echo "Error: Campos faltantes";
                return;
            }

            $usuario = mainModel::limpiar_cadena($_POST['login_email']);
            $clave = mainModel::limpiar_cadena($_POST['login_clave']);

            /*-- Comprobando campos vacíos - Checking empty fields --*/
            if (empty($usuario) || empty($clave)) {
                // Manejo del error de campos vacíos
                echo "Error: Campos vacíos";
                return;
            }

            /*-- Verificando integridad datos - Verifying data integrity --*/
            if (mainModel::verificar_datos("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}", $usuario)) {
                // Manejo del error de formato de correo electrónico incorrecto
                echo "Error: Formato de correo electrónico incorrecto";
                return;
            }

            $clave = mainModel::encryption($clave);

            /*-- Verificando datos de la cuenta - Verifying account details --*/
            $datos_cuenta = mainModel::datos_tabla("Unico", "cliente", "cliente_email", $usuario);

            if ($datos_cuenta->rowCount() == 1) {
                $row = $datos_cuenta->fetch();

                if ($row['cliente_clave'] == $clave) {
                    // Inicio de sesión exitoso
                    session_start();
                    $_SESSION['id_cliente'] = $row['cliente_id'];
                    $_SESSION['nombre_cliente'] = $row['cliente_nombre'];

                    // Redirigir a la página de inicio de clientes
                    echo "OK"; // Puedes enviar un indicador de éxito a tu JavaScript
                    return;
                } else {
                    // Manejo del error de contraseña incorrecta
                    echo "Error: Contraseña incorrecta";
                    return;
                }
            } else {
                // Manejo del error de cuenta no encontrada
                echo "Error: Cuenta no encontrada";
                return;
            }
        } else {
            // Manejo del error de solicitud incorrecta
            echo "Error: Solicitud incorrecta";
            return;
        }
    } /*-- Fin controlador - End controller --*/
}

?>
