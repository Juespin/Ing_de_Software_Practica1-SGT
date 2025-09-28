<?php
/* 
TAKEN FROM: https://github.com/oscaruhp/empleados
AUTHOR: Oscar Uh

MODIFIED AND ADAPTED BY: Angelower Santana-Velásquez
*/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/* Conexión a la base de datos */
$servidor = "localhost"; 
$usuario = "root"; 
$passwd = ""; 
$nombreBaseDatos = "sgt";
$conexionBD = new mysqli($servidor, $usuario, $passwd, $nombreBaseDatos);

// Verifica la conexión
if ($conexionBD->connect_error) {
    die(json_encode(["success" => 0, "error" => "Error de conexión: " . $conexionBD->connect_error]));
}


/* ---------------------- CONSULTAR POR CÓDIGO ---------------------- */
if (isset($_GET["consultarCodigo"])) {
    $codigo = $conexionBD->real_escape_string($_GET["consultarCodigo"]);
    $sqlUbicacion = mysqli_query($conexionBD, "SELECT * FROM ubicaciones WHERE codigo='$codigo'");
    if (mysqli_num_rows($sqlUbicacion) > 0) {
        $ubicacion = mysqli_fetch_assoc($sqlUbicacion);
        echo json_encode($ubicacion);
    } else {
        echo json_encode(["success" => 0]);
    }
    exit();
}

/* ---------------------- BORRAR UBICACION ---------------------- */
if (isset($_GET["borrar"])) {
    $codigo = intval($_GET["borrar"]);
    $sqlUbicacion = mysqli_query($conexionBD, "DELETE FROM ubicaciones WHERE codigo=$codigo");
    if ($sqlUbicacion) {
        echo json_encode(["success" => 1]);
    } else {  
        echo json_encode(["success" => 0]); 
    }
    exit();
}

/* ---------------------- INSERTAR UBICACION ---------------------- */
if (isset($_GET["insertar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $servicio = $data->servicio ?? '';
    $ubicacion = $data->ubicacion ?? '';
    $telefono = $data->telefono ?? '';

    if ($servicio != "" && $ubicacion != "" && $telefono != "") {        
        $sqlUbicacion = mysqli_query($conexionBD, 
            "INSERT INTO ubicaciones(servicio, ubicacion, telefono) VALUES('$servicio','$ubicacion','$telefono')");
        echo json_encode(["success" => $sqlUbicacion ? 1 : 0]);
    } else {
        echo json_encode(["success" => 0, "error" => "Datos incompletos"]);
    }
    exit();
}

/* ---------------------- ACTUALIZAR UBICACION ---------------------- */
if (isset($_GET["actualizar"])) { 
    $data = json_decode(file_get_contents("php://input"));
    $codigo = $data->codigo ?? intval($_GET["actualizar"]);
    $servicio = $data->servicio ?? '';
    $ubicacion = $data->ubicacion ?? '';
    $telefono = $data->telefono ?? '';

    $sqlUbicacion = mysqli_query($conexionBD, 
        "UPDATE ubicaciones SET servicio='$servicio', ubicacion='$ubicacion', telefono='$telefono' WHERE codigo='$codigo'");
    
    echo json_encode(["success" => $sqlUbicacion ? 1 : 0]);
    exit();
}

/* ---------------------- MOSTRAR TODAS LAS UBICACIONES ---------------------- */
$sqlUbicaciones = mysqli_query($conexionBD, "SELECT * FROM ubicaciones");
if (mysqli_num_rows($sqlUbicaciones) > 0) {
    $ubicaciones = mysqli_fetch_all($sqlUbicaciones, MYSQLI_ASSOC);
    echo json_encode($ubicaciones);
} else { 
    echo json_encode([["success" => 0]]); 
}

?>

