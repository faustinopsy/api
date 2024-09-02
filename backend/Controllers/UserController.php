<?php

namespace Backend\Api\Controllers;

use Backend\Api\Models\User;
use Backend\Api\Repositories\UserRepository;

class UserController {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function getAllUsers() {
        $users = $this->userRepository->getAllUsers();
        http_response_code(200);
        echo json_encode($users);
    }

    public function getUserById($id) {
        $user = $this->userRepository->getUserById($id);
        if ($user) {
            http_response_code(200);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }

    public function createUser() {
        $input = json_decode(file_get_contents('php://input'), true);
        $user = new User();
        $user->setNome($input['nome']);
        $user->setIdade($input['idade']);
        $createdUser = $this->userRepository->createUser($user);
        http_response_code(201);
        echo json_encode(['status' => true, 'message' => 'Usuário criado', 'user' => $createdUser->getId()]);
    }

    public function updateUser($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $user = $this->userRepository->getUserById($id);
        if ($user) {
            $user->setNome($input['nome'] ?? $user->getNome());
            $user->setIdade($input['idade'] ?? $user->getIdade());
            $updatedUser = $this->userRepository->updateUser($id, $user);
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'Usuário atualizado', 'user' => $updatedUser->getId()]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }

    public function deleteUser($id) {
        if ($this->userRepository->deleteUser($id)) {
            http_response_code(200);
            echo json_encode(['status' => true, 'message' => 'Usuário excluído']);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Usuário não encontrado']);
        }
    }
}
