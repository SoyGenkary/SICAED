<?php
/**
 * Maneja la subida de un archivo, lo renombra de forma única y lo guarda en un directorio específico.
 *
 * @param string $directorioDestino La carpeta de destino donde se guardá el archivo (ej. 'uploads/vehiculos/').
 * @param string $inputName El nombre del campo <input type="file"> en el formulario (ej. 'fotoVehiculo').
 * @return string La ruta relativa al archivo guardado si tiene éxito, o una cadena vacía si falla.
 */
function manejarSubidaArchivo($directorioDestino, $inputName) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $rutaAbsolutaDirectorio = $_SERVER['DOCUMENT_ROOT'] . '/SICAED/uploads/' . $directorioDestino;
    if (!file_exists($rutaAbsolutaDirectorio)) {
        if (!mkdir($rutaAbsolutaDirectorio, 0777, true)) {
            error_log("Error al crear el directorio: " . $rutaAbsolutaDirectorio);
            return '';
        }
    }

    $nombreOriginal = basename($_FILES[$inputName]["name"]);
    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nombreUnico = uniqid('', true) . '.' . strtolower($extension);
    $rutaCompletaArchivo = $rutaAbsolutaDirectorio . $nombreUnico;

    if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $rutaCompletaArchivo)) {
        return '../uploads/' . $directorioDestino . $nombreUnico;
    } else {
        error_log("Error al mover el archivo subido a: " . $rutaCompletaArchivo);
        return '';
    }
}


/**
 * Maneja la subida de múltiples archivos desde un input con atributo "multiple".
 * Guarda cada archivo en el directorio especificado y devuelve un array con las rutas relativas.
 *
 * @param string $directorioDestino Ej: 'uploads/documentos/'.
 * @param string $inputName Nombre del input file (ej. 'archivosAdjuntos[]').
 * @return array Array con las rutas relativas de los archivos guardados.
 */
function manejarMultiplesArchivos($directorioDestino, $inputName) {
    $archivosGuardados = [];

    // Elimina barra al final si existe
    $directorioDestino = rtrim($directorioDestino, '/');

    // Verifica que haya archivos
    if (!isset($_FILES[$inputName]) || !is_array($_FILES[$inputName]['name'])) {
        return $archivosGuardados;
    }

    // Construir ruta absoluta
    $rutaAbsolutaDirectorio = $_SERVER['DOCUMENT_ROOT'] . '/SICAED/uploads/' . $directorioDestino . '/';

    // Asegura que el directorio exista
    if (!file_exists($rutaAbsolutaDirectorio)) {
        if (!mkdir($rutaAbsolutaDirectorio, 0777, true)) {
            error_log("Error al crear el directorio: " . $rutaAbsolutaDirectorio);
            return [];
        }
    }

    $totalArchivos = count($_FILES[$inputName]['name']);

    for ($i = 0; $i < $totalArchivos; $i++) {
        if ($_FILES[$inputName]['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $nombreOriginal = basename($_FILES[$inputName]['name'][$i]);
        $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $nombreUnico = uniqid('', true) . '.' . strtolower($extension);
        $rutaCompleta = $rutaAbsolutaDirectorio . $nombreUnico;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'][$i], $rutaCompleta)) {
            $archivosGuardados[] = [
                "nombreOriginal" => $nombreOriginal,
                "ruta" => '../uploads/' . $directorioDestino . '/' . $nombreUnico
            ];
        } else {
            error_log("Error al mover el archivo: " . $nombreOriginal);
        }
    }

    return $archivosGuardados;
}


/**
 * Elimina archivos del sistema a partir de sus rutas relativas dentro del proyecto.
 *
 * @param array $rutas Array de rutas relativas, como '../uploads/vehiculos/archivo.pdf'.
 * @return array Lista de rutas eliminadas correctamente.
 */
function eliminarArchivosSubidos($rutas) {
    $rutasEliminadas = [];

    foreach ($rutas as $rutaRelativa) {
        // Quitar '../' del inicio si existe para evitar errores de path
        $rutaRelativa = ltrim($rutaRelativa, '/.');

        // Construir ruta absoluta basada en la raíz del proyecto
        $rutaAbsoluta = $_SERVER['DOCUMENT_ROOT'] . '/SICAED/' . $rutaRelativa;

        // Verifica que el archivo exista antes de intentar eliminarlo
        if (file_exists($rutaAbsoluta) && is_file($rutaAbsoluta)) {
            if (unlink($rutaAbsoluta)) {
                $rutasEliminadas[] = '../' . $rutaRelativa;
            } else {
                error_log("No se pudo eliminar el archivo: " . $rutaAbsoluta);
            }
        } else {
            error_log("Archivo no encontrado: " . $rutaAbsoluta);
        }
    }

    return $rutasEliminadas;
}
