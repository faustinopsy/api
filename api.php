<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); 
    exit();
}
$users = [
            'User1'=>['id'=> 1, 'nome'=>'eu', 'idade'=> 20 ],
            'User2'=>['id'=> 2, 'nome'=>'tu', 'idade'=> 32 ] 
         ];
$methodo = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if ($methodo === 'GET' && $uri === '/api.php/users') {
    http_response_code(200);
    echo json_encode(
            $users 
    );
} elseif ($methodo === 'POST' && $uri === '/api.php/users') {
    $input = json_decode(file_get_contents('php://input'), true);
    http_response_code(201);
    echo json_encode(
        ['message' => 'Usuário criado', 'user' => $input]
    );

} elseif ($methodo === 'PUT' && preg_match('/\/api.php\/users\/(\d+)/', $uri, $matches)) {
    $id = $matches[1];
    $input = json_decode(file_get_contents('php://input'), true);
    $users[$id] = $input;
    http_response_code(200);
    echo json_encode(
        ['status'=> true, 'message' => 'Usuário atualizado', 'users' => $input]
    );
} elseif ($methodo === 'DELETE' && preg_match('/\/api.php\/users\/(\d+)/', $uri, $matches)) {
    $id = $matches[1];
    unset($users[$id]);
    http_response_code(204);
    echo json_encode(
        ['status'=> true, 'message' => 'Usuário deletado']
    );
}
