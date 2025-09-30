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
$passwd = "12345678"; 
$nombreBaseDatos = "sgt";
$conexionBD = new mysqli($servidor, $usuario, $passwd, $nombreBaseDatos);

// Verifica la conexión
if ($conexionBD->connect_error) {
    die(json_encode(["success" => 0, "error" => "Error de conexión: " . $conexionBD->connect_error]));
}

/* ---------------------- CONSULTAR POR CÓDIGO ---------------------- */
if (isset($_GET["consultarCodigo"])) {
    $codigo = $conexionBD->real_escape_string($_GET["consultarCodigo"]);
    $sqlResponsable = mysqli_query($conexionBD, "SELECT * FROM responsables WHERE id='$codigo'");
    if (mysqli_num_rows($sqlResponsable) > 0) {
        $responsable = mysqli_fetch_assoc($sqlResponsable);
        echo json_encode($responsable);
    } else {
        echo json_encode(["success" => 0]);
    }
    exit();
}

/* ---------------------- BORRAR RESPONSABLE ---------------------- */
if (isset($_GET["borrar"])) {
    $codigo = intval($_GET["borrar"]);
    $sqlResponsable = mysqli_query($conexionBD, "DELETE FROM responsables WHERE id=$codigo");
    if ($sqlResponsable) {
        echo json_encode(["success" => 1]);
    } else {  
        echo json_encode(["success" => 0]); 
    }
    exit();
}

/* ---------------------- INSERTAR RESPONSABLE ---------------------- */
if (isset($_GET["insertar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $documento = $data->documento ?? '';
    $nombreape = $data->nombreape ?? '';
    $cargo = $data->cargo ?? '';
    $telefono = $data->telefono ?? '';

    if ($documento != "" && $nombreape != "" && $cargo != "" && $telefono != "") {        
        $sqlResponsable = mysqli_query($conexionBD, 
            "INSERT INTO responsables(Documento, Nombre_y_Apellidos, Cargo, Telefono) 
             VALUES('$documento','$nombreape','$cargo','$telefono')");
        echo json_encode(["success" => $sqlResponsable ? 1 : 0]);
    } else {
        echo json_encode(["success" => 0, "error" => "Datos incompletos"]);
    }
    exit();
}

/* ---------------------- ACTUALIZAR RESPONSABLE ---------------------- */
if (isset($_GET["actualizar"])) { 
    $data = json_decode(file_get_contents("php://input"));
    $codigo = $data->codigo ?? intval($_GET["actualizar"]);
    $documento = $data->documento ?? '';
    $nombreape = $data->nombreape ?? '';
    $cargo = $data->cargo ?? '';
    $telefono = $data->telefono ?? '';

    $sqlResponsable = mysqli_query($conexionBD, 
        "UPDATE responsables 
         SET Documento='$documento', Nombre_y_Apellidos='$nombreape', Cargo='$cargo', Telefono='$telefono' 
         WHERE id='$codigo'");
    
    echo json_encode(["success" => $sqlResponsable ? 1 : 0]);
    exit();
}

/* ---------------------- MOSTRAR TODOS LOS RESPONSABLES ---------------------- */
$sqlResponsables = mysqli_query($conexionBD, "SELECT * FROM responsables");
if (mysqli_num_rows($sqlResponsables) > 0) {
    $responsables = mysqli_fetch_all($sqlResponsables, MYSQLI_ASSOC);
    echo json_encode($responsables);
} else { 
    echo json_encode([["success" => 0]]); 
}

?>
