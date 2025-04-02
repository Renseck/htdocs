<?php

namespace controller;

use model\userModel;
use utils\validator;

class authController 
{
    private $userModel;

    // =============================================================================================
    public function __construct()
    {
        $this->userModel = new userModel();
        sessionController::startSession();
    }

    // =============================================================================================
    /**
     * Log the user in
     * @param string $email User email
     * @param string $password User password (unhashed)
     * @return array Result with success status and message
     */
    public function login(string $email, string $password) : array
    {
        // Validate input
        if (empty($email) || empty($password))
        {
            return  ["success" => false, "message" => "Email and password are required!"];
        }

        // Get user from database
        $user = $this->userModel->getUserByEmail($email);

        // Check if user exists and password matches
        if ($user && password_verify($password, $user["password"]))
        {
            // Put the user info in session (BUT NOT THE PASSWORD)
            unset($user["password"]);
            $_SESSION["user"] = $user;
            return ["success" => true, "message" => "Login successful!"];
        }

        return ["success" => false, "message" => "Invalid email or password!"];
    }

    // =============================================================================================
    /**
     * Register a new user
     * @param string $name User name
     * @param string $email User email
     * @param string $password User password
     * @param string $passwordRepeat User password confirmation
     * @return array Result with success status and message
     */
    public function register(string $name, string $email, string $password, string $passwordRepeat) : array
    {
        // Validate input
        if (!validator::validateRequired(["name" => $name, "email" => $email,
                                         "password" => $password, "passwordRepeat" => $passwordRepeat]))
        {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        // Valid email format
        if (!validator::validateEmail($email))
        {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Check if passwords match
        if (!validator::validatePasswordMatch($password, $passwordRepeat))
        {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }

        // Check if email is already taken
        if ($this->userModel->getUserByEmail($email))
        {
            return ['success' => false, 'message' => 'Email already taken'];
        }

        // Create user
        $userId = $this->userModel->createUser($name, $email, $password);

        if ($userId) 
        {
            return ['success' => true, 'message' => 'User created successfully'];
        }

        return ['success' => false, 'message' => 'Registration failed - please try again'];
    }

    // =============================================================================================
}