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
     * @return array|false User data or false if not found
     */
    public function getUserByEmail($email)
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
    public function createUser($name, $email, $password)
    {
        $userData = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        return $this->crud->create($userData);
    }

    // =============================================================================================
    /**
     * Check if email exists
     * @param string $email Email to check
     * @return bool True if email exists, false otherwise
     */
    public function emailExists($email)
    {
        $result = $this->crud->readOne(['email' => $email], 'COUNT(*) as count');
        return isset($result[0]["count"]) && $result[0]["count"] > 0;
    }
}