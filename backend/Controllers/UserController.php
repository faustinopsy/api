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
        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->getId(),
                'nome' => $user->getNome(),
                'idade' => $user->getIdade()
            ];
        }

        return $result;
    }

    public function getUserById($id) {
        $user = $this->userRepository->getUserById($id);
        if ($user) {
            return [
                'id' => $user->getId(),
                'nome' => $user->getNome(),
                'idade' => $user->getIdade()
            ];
        }
        return null;
    }

    public function createUser($userData) {
        $user = new User();
        $user->setNome($userData['nome']);
        $user->setIdade($userData['idade']);

        $createdUser = $this->userRepository->createUser($user);

        return [
            'id' => $createdUser->getId(),
            'nome' => $createdUser->getNome(),
            'idade' => $createdUser->getIdade()
        ];
    }

    public function updateUser($id, $userData) {
        $user = $this->userRepository->getUserById($id);

        if ($user) {
            if (isset($userData['nome'])) {
                $user->setNome($userData['nome']);
            }
            if (isset($userData['idade'])) {
                $user->setIdade($userData['idade']);
            }

            $updatedUser = $this->userRepository->updateUser($id, $user);

            return [
                'id' => $updatedUser->getId(),
                'nome' => $updatedUser->getNome(),
                'idade' => $updatedUser->getIdade()
            ];
        }

        return null;
    }

    public function deleteUser($id) {
        return $this->userRepository->deleteUser($id);
    }
}
