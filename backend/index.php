<?php
namespace Backend\Api;

require '../vendor/autoload.php';

use Backend\Api\Controllers\UserController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$controller = null;
$resposta = null;

switch (true) {
    case preg_match('/\/users$/', $uri):
        $controller = new UserController();
        switch ($method) {
            case 'GET':
                $resposta = $controller->getAllUsers();
                http_response_code(200);
                echo json_encode($resposta);
                break;

            case 'POST':
                $input = json_decode(file_get_contents('php://input'), true);
                $resposta = $controller->createUser($input);
                http_response_code(201);
                echo json_encode(['status' => true, 'message' => 'Usuário criado', 'user' => $resposta]);
                break;

            default:
                http_response_code(405);
                echo json_encode(['status' => false, 'message' => 'Método não permitido']);
                break;
        }
        break;

    case preg_match('/\/users\/(\d+)$/', $uri, $matches):
        $id = $matches[1];
        $controller = new UserController();
        switch ($method) {
            case 'GET':
                $resposta = $controller->getUserById($id);
                if ($resposta) {
                    http_response_code(200);
                    echo json_encode(['status' => true, $resposta]);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
                }
                break;

            case 'PUT':
                $input = json_decode(file_get_contents('php://input'), true);
                $resposta = $controller->updateUser($id, $input);
                if ($resposta) {
                    http_response_code(200);
                    echo json_encode(['status' => true, 'message' => 'Usuário atualizado', 'user' => $resposta]);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
                }
                break;

            case 'DELETE':
                if ($controller->deleteUser($id)) {
                    http_response_code(200);
                    echo json_encode(['status' => true, 'message' => 'Usuário Excluido']);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['status' => false, 'message' => 'Método não permitido']);
                break;
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['status' => false, 'message' => 'Rota não encontrada']);
        break;
}
