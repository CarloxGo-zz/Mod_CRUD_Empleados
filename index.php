<?php

// Cargamos Vendor
require __DIR__ . '/vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$fluent = new FluentPDO($pdo);

$action = isset($_GET['a']) ? $_GET['a'] : null;

switch($action) {
    case 'profesiones':
        header('Content-Type: application/json');
        print_r(json_encode(profesiones($fluent)));
        break;
    case 'listar':
        header('Content-Type: application/json');
        print_r(json_encode(listar($fluent)));
        break;
    case 'obtener':
        header('Content-Type: application/json');
        print_r(json_encode(obtener($fluent, $_GET['id'])));
        break;
    case 'registrar':
        header('Content-Type: application/json');
        $data = json_decode(utf8_encode(file_get_contents("php://input")), true);
        print_r(json_encode(registrar($fluent, $data)));
        break;
    case 'eliminar':
        header('Content-Type: application/json');
        print_r(json_encode(eliminar($fluent, $_GET['id'])));
        break;
}

function profesiones($fluent)
{
    return $fluent
         ->from('profesion')
         ->fetchAll();
}

function listar($fluent)
{
    return $fluent
         ->from('empleado')
         ->select('empleado.*, profesion.Nombre as Profesion')
         ->orderBy("id DESC")
         ->fetchAll();
}

function obtener($fluent, $id)
{
    return $fluent->from('empleado', $id)
                  ->select('empleado.*, profesion.Nombre as Profesion')
                              ->fetch();
}

function eliminar($fluent, $id)
{
    $fluent->deleteFrom('empleado', $id)
             ->execute();
    
    return true;
}

function registrar($fluent, $data)
{
    $data['FechaRegistro'] = date('Y-m-d');
    $fluent->insertInto('empleado', $data)
             ->execute();
    
    return true;
}