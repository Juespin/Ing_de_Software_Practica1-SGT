<?php
/* API CRUD para gestión de Equipos Médicos */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/* Conexión a la base de datos */
$servidor = "localhost"; 
$usuario = "root"; 
$passwd = "12345678"; 
$nombreBaseDatos = "sgt";
$conexionBD = new mysqli($servidor, $usuario, $passwd, $nombreBaseDatos);

if ($conexionBD->connect_error) {
    die(json_encode(["success" => 0, "error" => "Error de conexión: " . $conexionBD->connect_error]));
}

/* ---------------------- CONSULTAR POR Numero_Activo ---------------------- */
if (isset($_GET["consultarCodigo"])) {
    $codigo = $conexionBD->real_escape_string($_GET["consultarCodigo"]);
    $sqlEquipo = mysqli_query($conexionBD, "SELECT * FROM `equipos_medicos` WHERE `Numero_Activo`='$codigo'");
    if (mysqli_num_rows($sqlEquipo) > 0) {
        $equipo = mysqli_fetch_assoc($sqlEquipo);
        echo json_encode($equipo);
    } else {
        echo json_encode(["success" => 0]);
    }
    exit();
}

/* ---------------------- BORRAR EQUIPO ---------------------- */
if (isset($_GET["borrar"])) {
    $codigo = $conexionBD->real_escape_string($_GET["borrar"]);
    $sqlEquipo = mysqli_query($conexionBD, "DELETE FROM `equipos_medicos` WHERE `Numero_Activo`='$codigo'");
    echo json_encode(["success" => $sqlEquipo ? 1 : 0]);
    exit();
}

/* ---------------------- INSERTAR EQUIPO ---------------------- */
if (isset($_GET["insertar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $numero_activo = $data->numero_activo ?? '';
    $equipo = $data->equipo ?? '';
    $marca = $data->marca ?? '';
    $modelo = $data->modelo ?? '';
    $codigo_ubicacion = $data->codigo_ubicacion ?? '';
    $codigo_responsable = $data->codigo_responsable ?? '';

    if ($numero_activo != "" && $equipo != "" && $marca != "" && $modelo != "" && $codigo_ubicacion != "" && $codigo_responsable != "") {        
        $sqlEquipo = mysqli_query($conexionBD, 
            "INSERT INTO `equipos_medicos`(`Numero_Activo`, `Equipo`, `Marca`, `Modelo`, `Codigo Ubicacion`, `Codigo Responsable`) 
             VALUES('$numero_activo','$equipo','$marca','$modelo','$codigo_ubicacion','$codigo_responsable')");
        echo json_encode(["success" => $sqlEquipo ? 1 : 0]);
    } else {
        echo json_encode(["success" => 0, "error" => "Datos incompletos"]);
    }
    exit();
}

/* ---------------------- ACTUALIZAR EQUIPO ---------------------- */
if (isset($_GET["actualizar"])) { 
    $data = json_decode(file_get_contents("php://input"));
    $numero_activo = $data->numero_activo ?? '';
    $equipo = $data->equipo ?? '';
    $marca = $data->marca ?? '';
    $modelo = $data->modelo ?? '';
    $codigo_ubicacion = $data->codigo_ubicacion ?? '';
    $codigo_responsable = $data->codigo_responsable ?? '';

    $sqlEquipo = mysqli_query($conexionBD, 
        "UPDATE `equipos_medicos` 
         SET `Equipo`='$equipo', `Marca`='$marca', `Modelo`='$modelo', 
             `Codigo Ubicacion`='$codigo_ubicacion', `Codigo Responsable`='$codigo_responsable' 
         WHERE `Numero_Activo`='$numero_activo'");
    
    echo json_encode(["success" => $sqlEquipo ? 1 : 0]);
    exit();
}

/* ---------------------- MOSTRAR TODOS LOS EQUIPOS ---------------------- */
$sqlEquipos = mysqli_query($conexionBD, "SELECT * FROM `equipos_medicos`");
if (mysqli_num_rows($sqlEquipos) > 0) {
    $equipos = mysqli_fetch_all($sqlEquipos, MYSQLI_ASSOC);
    echo json_encode($equipos);
} else { 
    echo json_encode([["success" => 0]]); 
}
?>
