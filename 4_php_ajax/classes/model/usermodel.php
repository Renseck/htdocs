<?php

namespace model;

use database\CrudOperations;

class userModel 
{
    private $crud;

    // =============================================================================================
    public function __construct()
    {
        $this->crud = new CrudOperations("users");
    }

    // =============================================================================================
    /**
     * Get user by email
     * @param string $email User email
     * @return array|bool User data or false if not found
     */
    public function getUserByEmail(string $email) : array|bool
    {
        $user = $this->crud->readOne(["email" => $email]);
        return $user;
    }

    // =============================================================================================
    /**
     * Create a new user
     * @param string $name User name
     * @param string $email User email
     * @param string $password User password (will be hashed)
     * @return int|bool ID of created user or false on failure
     */
    public function createUser(string $name, string $email, string $password) : int|bool
    {
        $userData = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        return $this->crud->create($userData);
    }

    // =============================================================================================
    
}