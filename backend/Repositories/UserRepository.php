<?php

namespace Backend\Api\Repositories;

use Backend\Api\Models\User;

class UserRepository {
    private $users = [];

    public function __construct() {
        $this->users = [
            1 => new User(1, 'eu', 20),
            2 => new User(2, 'tu', 32)
        ];
    }

    public function getAllUsers() {
        return $this->users;
    }

    public function getUserById($id) {
        return $this->users[$id] ?? null;
    }

    public function createUser(User $user) {
        $id = count($this->users) + 1;
        $user->setId($id);
        $this->users[$id] = $user;
        return $user;
    }

    public function updateUser($id, User $user) {
        if (isset($this->users[$id])) {
            $this->users[$id] = $user;
            return $this->users[$id];
        }
        return null;
    }

    public function deleteUser($id) {
        if (isset($this->users[$id])) {
            unset($this->users[$id]);
            return true;
        }
        return false;
    }
}
