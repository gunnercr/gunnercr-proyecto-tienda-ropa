<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Headers: Content-Type, Authorization');  
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');  


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
    http_response_code(200);
    exit;
}

// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'tiendaropa';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// Ruta solicitada
$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Manejo de rutas principales
if ($path[0] === 'prenda') {
    $id = $path[1] ?? null;

    switch ($method) {
        case 'GET':
            if ($id) {
                obtenerPrenda($pdo, $id);
            } else {
                obtenerPrendas($pdo);
            }
            break;

        case 'POST':
            crearprenda($pdo);
            break;

        case 'PUT':
            if ($id) {
                actualizarprenda($pdo, $id);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para actualizar"]);
            }
            break;

        case 'DELETE':
            if ($id) {
                eliminarPrenda($pdo, $id);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para eliminar"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }
}if ($path[0] === 'marca'){
    $id = $path[1] ?? null;

    switch ($method) {
        case 'GET':
            if ($id) {
                obtenerMarca($pdo, $id);
            } else {
                obtenerMarcas($pdo);
            }
            break;

        case 'POST':
            crearMarca($pdo);
            break;

        case 'PUT':
            if ($id) {
                actualizarMarca($pdo, $id);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para actualizar"]);
            }
            break;

        case 'DELETE':
            if ($id) {
                eliminarMarca($pdo, $id);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para eliminar"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }
}
if ($path[0] === 'venta'){
    $id = $path[1] ?? null;

    switch ($method) {
        case 'GET':
            if ($id) {
                obtenerVenta($pdo, $id);
            } else {
                obtenerVentas($pdo);
            }
            break;

        case 'POST':
            crearVenta($pdo);
            break;

        case 'PUT':
            if ($id) {
                actualizarVenta($pdo, $id);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para actualizar"]);
            }
            break;

        case 'DELETE':
            if ($id) {
                eliminarVenta($pdo, $id);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID requerido para eliminar"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Método no permitido"]);
    }}



// Funciones CRUD prendas
function obtenerPrendas($pdo)
{
    $stmt = $pdo->query('SELECT * FROM prenda');
    $prenda = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($prenda);
}

function obtenerPrenda($pdo, $id)
{
    $stmt = $pdo->prepare('SELECT * FROM prenda WHERE id_prenda = :id_prenda');
    $stmt->execute(['id_prenda' => $id]);
    $prenda = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prenda) {
        echo json_encode($prenda);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Prenda no encontrado"]);
    }
}

function crearPrenda($pdo)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre_prenda'], $data['talla'], $data['color'], $data['stock'], $data['id_marca'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO prenda (nombre_prenda, talla, color, stock, id_marca) VALUES (:nombre_prenda, :talla, :color, :stock, :id_marca)');
    $stmt->execute([
        'nombre_prenda' => $data['nombre_prenda'],
        'talla' => $data['talla'],
        'color' => $data['color'],
        'stock' => $data['stock'],
        'id_marca' => $data['id_marca']
    ]);

    echo json_encode(["message" => "Prenda creado", "id" => $pdo->lastInsertId()]);
}

function actualizarPrenda($pdo, $id)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre_prenda'], $data['talla'], $data['color'], $data['stock'], $data['id_marca'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $stmt = $pdo->prepare('UPDATE prenda SET nombre_prenda = :nombre_prenda, talla = :talla, color = :color, stock = :stock, id_marca = :id_marca WHERE id_prenda = :id_prenda');
    $stmt->execute([
        'id_prenda' => $id,
        'nombre_prenda' => $data['nombre_prenda'],
        'talla' => $data['talla'],
        'color' => $data['color'],
        'stock' => $data['stock'],
        'id_marca' => $data['id_marca']
    ]);

    echo json_encode(["message" => "Prenda actualizado"]);
}

function eliminarPrenda($pdo, $id)
{
    $stmt = $pdo->prepare('DELETE FROM prenda WHERE id_prenda = :id_prenda');
    $stmt->execute(['id_prenda' => $id]);

    echo json_encode(["message" => "Prenda eliminado"]);
}

// Funciones CRUD Marca
function obtenerMarcas($pdo)
{
    $stmt = $pdo->query('SELECT * FROM marca');
    $marca = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($marca);
}

function obtenerMarca($pdo, $id)
{
    $stmt = $pdo->prepare('SELECT * FROM marca WHERE id_marca = :id_marca');
    $stmt->execute(['id_marca' => $id]);
    $marca = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($marca) {
        echo json_encode($marca);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Marca no encontrado"]);
    }
}

function crearMarca($pdo)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre_marca'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO marca (nombre_marca) VALUES (:nombre_marca)');
    $stmt->execute([
        'nombre_marca' => $data['nombre_marca']
    ]);

    echo json_encode(["message" => "Marca creado", "id" => $pdo->lastInsertId()]);
}

function actualizarMarca($pdo, $id)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre_marca'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $stmt = $pdo->prepare('UPDATE marca SET nombre_marca = :nombre_marca WHERE id_marca = :id_marca');
    $stmt->execute([
        'id_marca' => $id,
        'nombre_marca' => $data['nombre_marca'],
    ]);

    echo json_encode(["message" => "Marca actualizado"]);
}

function eliminarMarca($pdo, $id)
{
    $stmt = $pdo->prepare('DELETE FROM marca WHERE id_marca = :id_marca');
    $stmt->execute(['id_marca' => $id]);

    echo json_encode(["message" => "Marca eliminado"]);
}


// Funciones CRUD Venta
function obtenerVentas($pdo)
{
    $stmt = $pdo->query('SELECT * FROM venta');
    $venta = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($venta);
}

function obtenerVenta($pdo, $id)
{
    $stmt = $pdo->prepare('SELECT * FROM venta WHERE id_venta = :id_venta');
    $stmt->execute(['id_venta' => $id]);
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($venta) {
        echo json_encode($venta);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Venta no encontrado"]);
    }
}

function crearVenta($pdo)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_prenda'], $data['cantidad'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO venta (id_prenda, cantidad) VALUES (:id_prenda, :cantidad)');
    $stmt->execute([
        'id_prenda' => $data['id_prenda'],
        'cantidad' => $data['cantidad']
    ]);

    echo json_encode(["message" => "Venta creado", "id" => $pdo->lastInsertId()]);
}

function actualizarVenta($pdo, $id)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id_prenda'], $data['cantidad'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        return;
    }


    $stmt = $pdo->prepare('UPDATE venta SET id_prenda = :id_prenda, cantidad = :cantidad WHERE id_venta = :id_venta');
    $stmt->execute([
        'id_venta' => $id,
        'id_prenda' => $data['id_prenda'],
        'cantidad' => $data['cantidad']
    ]);

    echo json_encode(["message" => "Venta actualizado"]);
}

function eliminarVenta($pdo, $id)
{
    $stmt = $pdo->prepare('DELETE FROM venta WHERE id_venta = :id_venta');
    $stmt->execute(['id_venta' => $id]);

    echo json_encode(["message" => "Venta eliminado"]);
}
?>


