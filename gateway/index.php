<?php
function forwardRequest($url, $method, $data = null) {
    $ch = curl_init();
    // Configura a URL e o método HTTP para encaminhar a requisição
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    // Se houver dados, adiciona-os ao corpo da requisição
    if ($method === 'POST' || $method === 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
    }
    // Configura para receber a resposta como string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Executa a requisição e captura a resposta
    $response = curl_exec($ch);
    // Captura o código de resposta HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // Fecha a sessão cURL
    curl_close($ch);
    // Retorna a resposta e o código HTTP
    return [
            'response' => $response,
            'http_code' => $httpCode
           ];
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($uri);
$path = $parsedUrl['path'];
// Ajusta a URL para encaminhar para a API rodando na porta 8080
$targetUrl = "http://localhost:8080" . $path;

switch ($method) {
    case 'GET':
        // Encaminha a requisição GET para a API na porta 8080
        $response = forwardRequest($targetUrl, 'GET');
        break;
    case 'POST':
        // Lê o corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);
        // Encaminha a requisição POST para a API na porta 8080
        $response = forwardRequest($targetUrl, 'POST', $data);
        break;
    case 'PUT':
        // Lê o corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);
        // Encaminha a requisição PUT para a API na porta 8080
        $response = forwardRequest($targetUrl, 'PUT', $data);
        break;
    case 'DELETE':
        // Encaminha a requisição DELETE para a API na porta 8080
        $response = forwardRequest($targetUrl, 'DELETE');
        break;
    default:
        http_response_code(405); // Método não permitido
        echo json_encode(['message' => 'Método não permitido']);
        exit();
}

// Configura o código de resposta HTTP do Gateway com base na resposta da API
http_response_code($response['http_code']);
// Retorna a resposta da API original
echo $response['response'];