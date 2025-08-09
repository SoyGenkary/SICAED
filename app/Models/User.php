<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Services/FileManager.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/AuditLogger.php';

class User {

    public static function login($conn, $data) {
        // CAPTURA DE LOS DATOS DEL FORMULARIO
        $correo_electronico = mysqli_real_escape_string($conn, $data['login-user']);
        $contrasena = mysqli_real_escape_string($conn, $data['login-password']);
        $sqluser = "SELECT id_usuario, contrasenia_hash, ultimo_login, email, rol FROM usuarios WHERE email = '$correo_electronico'";

        // REALIZAR SOLICITUD
        $resultado = $conn->query($sqluser);
        $rows = $resultado->num_rows;

        // VERIFICAR SI LA CUENTA EXISTE, LA CONTRASEÑA ES CORRECTA Y SI EL LOGEO ES CORRECTO
        if ($rows > 0) {
            $row = $resultado->fetch_assoc();
            $id = $row['id_usuario'];
            $rol = $row['rol'];
            
            // COMBROBAMOS SI EL INICIO DE SESION FUE EXITOSO
            if (!password_verify($contrasena, $row["contrasenia_hash"])) {
                return [
                    'success' => false,
                    'message' => 'Contraseña incorrecta!',
                ];
            } else {
                // OBTENEMOS LA FECHA ACTUAL Y ACTUALIZAMOS LA BASE DE DATOS
                $fecha_actual = date('Y-m-d H:i:s');
                $sqluser = "UPDATE usuarios SET ultimo_login = '$fecha_actual' WHERE id_usuario = '$id'";
                $conn->query($sqluser);
                
                // GUARDAMOS LA SESION
                session_start();
                $_SESSION['id'] = $id;
                $_SESSION['rol'] = $rol;

                // Registramos la accion
                AuditLogger::registrarAccion($conn, $id, "USUARIO:LOGIN", 'usuarios', 'ultimo_login', $row['ultimo_login'], $fecha_actual, "Ha iniciado sesión correctamente.");

                // header("Location: ../public/dashboard.php");
                return [
                'success' => true,
                'message' => 'Cuenta encontrada!',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Cuenta no encontrada!',
            ];
        }
    }

    public static function comprobarContrasenia($conn, $data) {
        $idUser = mysqli_real_escape_string($conn, $data['id_user']);
        $contraseniaIngresada = mysqli_real_escape_string($conn, $data['password']);

        $sqluser = "SELECT contrasenia_hash FROM usuarios WHERE id_usuario = '$idUser'";

        // REALIZAR SOLICITUD
        $resultado = $conn->query($sqluser);
        $rows = $resultado->num_rows;

        // VERIFICAR SI LA CONTRASEÑA ES CORRECTA
        if ($rows > 0) {
            $row = $resultado->fetch_assoc();
            // COMBROBAMOS SI EL INICIO DE SESION FUE EXITOSO
            if (!password_verify($contraseniaIngresada, $row["contrasenia_hash"])) {
                return [
                    'success' => false,
                    'message' => 'Contraseña incorrecta!',
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Contraseña correcta!',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'El ID de tu cuenta no existe!',
            ];
        }
    }

    public static function crear($conn, $data) {
        // CAPTURA DE LOS DATOS DEL FORMULARIO
        $nombre_completo = mysqli_real_escape_string($conn, $data['register-name']);
        $cedula = mysqli_real_escape_string($conn, $data['register-cedula']);
        $telefono = mysqli_real_escape_string($conn, $data['register-telefono']);
        $correo_electronico = mysqli_real_escape_string($conn, $data['register-email']);
        $contrasena = mysqli_real_escape_string($conn, $data['register-password']);
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $clave_maestra = mysqli_real_escape_string($conn, $data['register-master-key']);
        $clave_valida = false;

        // VERIFICAR SI EL CORREO ELECTRONICO YA ESTA REGISTRADO
        $sqluser = "SELECT id_usuario FROM usuarios WHERE email = '$correo_electronico'";
        $sqluser_cm = "SELECT * FROM clavesmaestras";
        // REALIZAR SOLICITUD
        $resultado = $conn->query($sqluser);
        $resultado_cm = $conn->query($sqluser_cm);

        if ($resultado->num_rows > 0) {
            return [
                'success' => false,
                'message' => 'El correo electrónico ya está registrado'
            ];
        } else {
            // Iteramos sobre cada clave maestra de la base de datos.
            while ($row_cm = $resultado_cm->fetch_assoc()) {
                // Comparamos la clave ingresada por el usuario con la clave hasheada de la BD.
                if (password_verify($clave_maestra, $row_cm["clave"])) {
                    // ¡Coincidencia encontrada! Marcamos la clave como válida y salimos del bucle.
                    $clave_valida = true;
                    break;
                }
            }

            if ($clave_valida) {
                // La clave maestra es correcta, procedemos a registrar al usuario.
                $sql_insert_user = "INSERT INTO usuarios (email, contrasenia_hash, nombre, cedula, telefono) VALUES ('$correo_electronico', '$contrasena_hash', '$nombre_completo', '$cedula', '$telefono')";

                if ($conn->query($sql_insert_user)) {
                    // Registramos la accion
                    AuditLogger::registrarAccion($conn, 1, "USUARIO:CREAR", 'usuarios', '*', '', '', "Se ha creado un nuevo usuario con correo: $correo_electronico y Cédula: $cedula");

                    return [
                        'success' => true,
                        'message' => 'Usuario creado',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Ocurrio un error!',
                    ];
                }
            } else {
                // Si el bucle terminó y nunca se encontró una coincidencia.
                return [
                        'success' => false,
                        'message' => 'Clave maestra incorrecta!!',
                    ];
            }
        }
    }

    public static function modificar($conn, $data) {
        $nuevoNombre = mysqli_real_escape_string($conn, $data['nombreUser']);
        $nuevoEmail = mysqli_real_escape_string($conn, $data['emailUser']);
        $nuevoCedula = mysqli_real_escape_string($conn, $data['cedulaUser']);
        $nuevoTelefono = mysqli_real_escape_string($conn, $data['telefonoUser']);

        if (isset($data['passwordUser'])) {
            $nuevaContrasenia = mysqli_real_escape_string($conn, $data['passwordUser']);
            $nuevaContrasenia = password_hash($nuevaContrasenia, PASSWORD_DEFAULT);
        }
        if (isset($data['rolUser'])) {
            $rol = mysqli_real_escape_string($conn, $data['rolUser']);
        }

        $id_usuario = $data['id_user'];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // VERIFICAR SI EL CORREO ELECTRONICO YA ESTA REGISTRADO
        $sqluser = "SELECT id_usuario FROM usuarios WHERE email = '$nuevoEmail' AND id_usuario != '$id_usuario'";
        
        // REALIZAR SOLICITUD
        $resultado = $conn->query($sqluser);
        if ($resultado->num_rows > 0) {
            return [
                'success' => false,
                'message' => 'El correo electrónico ya está registrado'
            ];
        }

        if (isset($nuevaContrasenia) && isset($rol)) {
            if(empty($nuevaContrasenia)) {
                $sqluser = "UPDATE usuarios SET nombre = '$nuevoNombre', email = '$nuevoEmail', cedula = '$nuevoCedula', telefono = '$nuevoTelefono', rol = '$rol' WHERE id_usuario = '$id_usuario'";
            } else {
                $sqluser = "UPDATE usuarios SET nombre = '$nuevoNombre', email = '$nuevoEmail', cedula = '$nuevoCedula', telefono = '$nuevoTelefono', contrasenia_hash = '$nuevaContrasenia', rol = '$rol' WHERE id_usuario = '$id_usuario'";
            }
        } else {
            $sqluser = "UPDATE usuarios SET nombre = '$nuevoNombre', email = '$nuevoEmail', cedula = '$nuevoCedula', telefono = '$nuevoTelefono' WHERE id_usuario = '$id_usuario'";
        }

        if ($conn->query($sqluser)) {
            // Registramos la accion
            AuditLogger::registrarAccion($conn, $id_usuario, "USUARIO:MODIFY", 'usuarios', '*', '', '', "Se ha actualizado los datos del perfil.");

            return [
                'success' => true,
                'message' => 'Se ha modificado el usuario correctamente!',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ha ocurrido un error al actualizar los datos!'
            ];
        }
    }

    public static function cambiarfoto($conn, $data) {
        $rutaFoto = manejarSubidaArchivo('Users/images/', 'photoPerfil');
        $id_usuario = $data['id'];

        # Verificamos si el usuario ya tiene una foto asignada (si la tiene, la borramos del almacenamiento)
        $sqlUserPhoto = "SELECT ruta_img FROM usuarios WHERE id_usuario = '$id_usuario'";
        $resultado = $conn->query($sqlUserPhoto);

        if ($resultado && $resultado->num_rows > 0) {
            $resultado = $resultado->fetch_assoc();
            if (!empty($resultado['ruta_img'])) {
                $rutaImagenAntigua = [$resultado['ruta_img']];
                eliminarArchivosSubidos(($rutaImagenAntigua));
            }
        }

        $sqluser = "UPDATE usuarios SET ruta_img = '$rutaFoto' WHERE id_usuario = '$id_usuario'";
        if ($conn->query($sqluser)) {
            // Registramos la accion
            AuditLogger::registrarAccion($conn, $id_usuario, "USUARIO:MODIFY", 'usuarios', '*', '', '', "Se ha actualizado la foto de perfil.");
            return [
                'success' => true,
                'message' => 'Se ha actualizado la foto de perfil correctamente!',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se ha podido actualizar la foto de perfil!',
            ];
        }
    }

    public static function borrarfoto($conn, $data) {
        $id_usuario = $data['id'];

        # Verificamos si el usuario ya tiene una foto asignada (si la tiene, la borramos del almacenamiento)
        $sqlUserPhoto = "SELECT ruta_img FROM usuarios WHERE id_usuario = '$id_usuario'";
        $resultado = $conn->query($sqlUserPhoto);

        if ($resultado && $resultado->num_rows > 0) {
            $resultado = $resultado->fetch_assoc();
            if (!empty($resultado['ruta_img'])) {
                $rutaImagenAntigua = [$resultado['ruta_img']];
                eliminarArchivosSubidos(($rutaImagenAntigua));
            }
        }

        $sqluser = "UPDATE usuarios SET ruta_img = '' WHERE id_usuario = '$id_usuario'";

        if ($conn->query($sqluser)) {
            // Registramos la accion
            AuditLogger::registrarAccion($conn, $id_usuario, "USUARIO:MODIFY", 'usuarios', '*', '', '', "Se ha eliminado la foto de perfil.");
            return [
                'success' => true,
                'message' => 'Se ha actualizado la foto de perfil correctamente!',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se ha podido actualizar la foto de perfil!',
            ];
        }
    }

    public static function eliminarCuenta($conn, $data) {
        $id_usuario = mysqli_real_escape_string($conn, $data['id_user']);

        // Obtener información del usuario antes de eliminar (para auditoría)
        $sqlSelect = "SELECT email, nombre, cedula FROM usuarios WHERE id_usuario = '$id_usuario'";
        $resultado = $conn->query($sqlSelect);
        $infoUsuario = $resultado && $resultado->num_rows > 0 ? $resultado->fetch_assoc() : [];

        // Eliminar la foto de perfil si existe
        $sqlUserPhoto = "SELECT ruta_img FROM usuarios WHERE id_usuario = '$id_usuario'";
        $resultadoFoto = $conn->query($sqlUserPhoto);
        if ($resultadoFoto && $resultadoFoto->num_rows > 0) {
            $rowFoto = $resultadoFoto->fetch_assoc();
            if (!empty($rowFoto['ruta_img'])) {
                eliminarArchivosSubidos([$rowFoto['ruta_img']]);
            }
        }

        // Eliminar el usuario de la base de datos
        $sqlDelete = "DELETE FROM usuarios WHERE id_usuario = '$id_usuario'";
        if ($conn->query($sqlDelete)) {
            // Registrar la acción en la auditoría
            $detalle = "Se ha eliminado permanentemente la cuenta del usuario: ";
            if ($infoUsuario) {
                $detalle .= "Correo: {$infoUsuario['email']}, Nombre: {$infoUsuario['nombre']}, Cédula: {$infoUsuario['cedula']}";
            }
            AuditLogger::registrarAccion($conn, 1, "USUARIO:ELIMINAR", 'usuarios', '*', '', '', $detalle);

            // Cerrar sesión si el usuario eliminado es el actual
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['id']) && $_SESSION['id'] == $id_usuario) {
                session_destroy();
            }

            return [
                'success' => true,
                'message' => 'La cuenta ha sido eliminada permanentemente.',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se pudo eliminar la cuenta.',
            ];
        }
    }

    public static function obtenerPerfilPorId($conn, $data) {
        $id_usuario = mysqli_real_escape_string($conn, $data['id_user']);
        $sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario' LIMIT 1";
        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $perfil = $resultado->fetch_assoc();

            // Obtener historial de auditoría (últimas 10 acciones)
            $sqlHistorial = "SELECT accion, descripcion, fecha FROM registroauditorias WHERE id_usuario = '$id_usuario' ORDER BY id_registro DESC LIMIT 10";
            $resHistorial = $conn->query($sqlHistorial);
            $historial = [];
            if ($resHistorial && $resHistorial->num_rows > 0) {
                while ($row = $resHistorial->fetch_assoc()) {
                    $historial[] = $row;
                }
            }

            return [
                'success' => true,
                'perfil' => $perfil,
                'historial' => $historial
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ];
        }
    }
}
