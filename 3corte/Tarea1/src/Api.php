<?php
require_once("conexion.php");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ejercicio";
$db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    $response = ['error' => 'Error de conexión con la base de datos'];
    http_response_code(500);
} else {
    $funcion = isset($_REQUEST['funcion']) ? $_REQUEST['funcion'] : null;
    $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
    switch ($method) {
        case 'POST':
            switch ($funcion) {
                case 'creaContacto':
                    $response = crearContacto($db);
                    break;
                case 'buscarContacto':
                    $response = buscarContacto($db);
                    break;
                case 'modificarContacto':
                    $response = modificarContacto($db);
                    break;
                default:
                    $response = ['error' => 'Función no encontrada'];
                    http_response_code(400);
                    break;
            }
            break;
        case 'GET':
            switch ($funcion) {
                case 'buscarContacto':
                    $response = buscarContacto($db);
                    break;
                case 'mostrarTodosContactos':
                    $response = mostrarTodosContactos($db);
                    break;
                default:
                    $response = ['error' => 'Función no encontrada'];
                    http_response_code(400);
                    break;
            }
            break;
        case 'DELETE':
            if ($funcion === 'eliminarContacto') {
                $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : null;
                if (!empty($nombre)) {
                    $input_data = ['nombre' => $nombre];
                    $response = eliminarContacto($db, $input_data);
                } else {
                    $response = ['error' => 'Parámetro "nombre" obligatorio'];
                    http_response_code(400);
                }
            } else {
                $response = ['error' => 'Función no encontrada'];
                http_response_code(400);
            }
            break;
        case 'PUT':
            if ($funcion === 'modificarContacto') {
                $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : null;
                if (!empty($nombre)) {
                    $nuevo_nombre = isset($_REQUEST['nuevo_nombre']) ? $_REQUEST['nuevo_nombre'] : null;
                    $nuevo_apellidos = isset($_REQUEST['nuevo_apellidos']) ? $_REQUEST['nuevo_apellidos'] : null;
                    $nuevo_direccion = isset($_REQUEST['nuevo_direccion']) ? $_REQUEST['nuevo_direccion'] : null;

                    if (!empty($nuevo_nombre)) {
                        $input_data = [
                            'nombre' => $nombre,
                            'nuevo_nombre' => $nuevo_nombre,
                            'nuevo_apellidos' => $nuevo_apellidos,
                            'nuevo_direccion' => $nuevo_direccion
                        ];
                        $response = modificarContacto($db, $input_data);
                    } else {
                        $response = ['error' => 'Parámetro "nuevo_nombre" obligatorio'];
                        http_response_code(400);
                    }
                } else {
                    $response = ['error' => 'Parámetro "nombre" obligatorio'];
                    http_response_code(400);
                }
            } else {
                $response = ['error' => 'Función no encontrada'];
                http_response_code(400);
            }
            break;
        default:
            $response = ['error' => 'Método no admitido'];
            http_response_code(400);
            break;
    }
}

header('Content-Type: application/json');
echo json_encode($response);

function crearContacto($db) {
    $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($db, $_POST['apellidos']);
    $direccion = mysqli_real_escape_string($db, $_POST['direccion']);
    $sql = "INSERT INTO escritorio (nombre, apellidos, direccion) VALUES ('$nombre', '$apellidos', '$direccion')";
    $stmt = $db->prepare($sql);
    if ($stmt->execute()) {
        $response = [
            "message" => "Contacto creado exitosamente",
            "status_code" => 200,
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "direccion" => $direccion
        ];
    } else {
        $response = [
            "message" => "Ocurrió un error al crear el contacto",
            "status_code" => 500
        ];
    }

    return $response;
}

function buscarContacto($db) {
    $nombre = mysqli_real_escape_string($db, $_GET['nombre']);
    $sql = 'SELECT * FROM escritorio WHERE nombre = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $nombre);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $contacto = $result->fetch_assoc();

        if ($contacto) {
            $response = [
                "message" => "Contacto encontrado exitosamente",
                "contacto" => $contacto,
                "status_code" => 200
            ];
        } else {
            $response = [
                "message" => "Contacto no encontrado",
                "status_code" => 404
            ];
        }
    } else {
        $response = [
            "message" => "Ocurrió un error interno del servidor",
            "status_code" => 500
        ];
    }

    return $response;
}

function eliminarContacto($db, $input_data) {
    $response = [];
    if (isset($input_data['nombre'])) {
        $nombre = mysqli_real_escape_string($db, $input_data['nombre']);
        $sql_check = 'SELECT * FROM escritorio WHERE nombre = ?';
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->bind_param('s', $nombre);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            $sql_delete = 'DELETE FROM escritorio WHERE nombre = ?';
            $stmt_delete = $db->prepare($sql_delete);
            $stmt_delete->bind_param('s', $nombre);

            if ($stmt_delete->execute()) {
                $response = [
                    "message" => "Contacto $nombre eliminado exitosamente",
                    "status_code" => 200
                ];
            } else {
                $response = ["message" => "Ocurrió un error al eliminar el contacto", "status_code" => 500];
            }
        } else {
            $response = ["message" => "Contacto $nombre no encontrado", "status_code" => 404];
        }
    } else {
        $response = ["message" => "Solicitud no válida: el parámetro 'nombre' es obligatorio", "status_code" => 400];
    }

    return $response;
}

function modificarContacto($db, $input_data) {
    if (isset($input_data['nombre'])) {
        $nombre = mysqli_real_escape_string($db, $input_data['nombre']);
        $nuevo_nombre = mysqli_real_escape_string($db, $input_data['nuevo_nombre']);
        $nuevo_apellidos = mysqli_real_escape_string($db, $input_data['nuevo_apellidos']);
        $nuevo_direccion = mysqli_real_escape_string($db, $input_data['nuevo_direccion']);
        if (empty($nombre) || empty($nuevo_nombre)) {
            $response = ["message" => "Los campos 'nombre' y 'nuevo_nombre' son obligatorios.", "status_code" => 400];
        } else {
            $sql_select = 'SELECT nombre, apellidos, direccion FROM escritorio WHERE nombre = ?';
            $stmt_select = $db->prepare($sql_select);
            $stmt_select->bind_param('s', $nombre);
            if (!$stmt_select->execute()) {
                $error = mysqli_stmt_error($stmt_select);
                $response = ["message" => "Error al verificar el contacto: " . $error, "status_code" => 500];
            }
            $result = $stmt_select->get_result();
            $contacto = $result->fetch_assoc();
            if (!$contacto) {
                $response = ["message" => "El contacto no existe", "status_code" => 400];
            } else {
                $sql_update = 'UPDATE escritorio SET nombre = ?, apellidos = ?, direccion = ? WHERE nombre = ?';
                $stmt_update = $db->prepare($sql_update);
                $stmt_update->bind_param('ssss', $nuevo_nombre, $nuevo_apellidos, $nuevo_direccion, $nombre);

                if ($stmt_update->execute()) {
                    $response = [
                        "message" => "Contacto modificado exitosamente",
                        "status_code" => 200,
                        "nombre" => $nuevo_nombre,
                        "apellidos" => $nuevo_apellidos,
                        "direccion" => $nuevo_direccion
                    ];
                } else {
                    $error = mysqli_stmt_error($stmt_update);
                    $response = ["message" => "Error al modificar el contacto: " . $error, "status_code" => 500];
                }
            }
        }
    } else {
        $response = ["message" => "Solicitud no válida: el parámetro 'nombre' es obligatorio", "status_code" => 400];
    }

    return $response;
}


function mostrarTodosContactos($db) {
    $sql = 'SELECT * FROM escritorio';
    $result = $db->query($sql);
    if ($result) {
        $contactos = $result->fetch_all(MYSQLI_ASSOC);
        $response = [
            "message" => "Contactos encontrados exitosamente",
            "contactos" => $contactos,
            "status_code" => 200
        ];
    } else {
        $response = [
            "message" => "Ocurrió un error interno del servidor",
            "status_code" => 500
        ];
    }

    return $response;
}
?>
