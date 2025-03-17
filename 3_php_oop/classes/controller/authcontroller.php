<?php

namespace controller;

use model\userModel;

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
    public function login ($email, $password) 
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
    public function register ($name, $email, $password, $passwordRepeat) 
    {
        // Validate input
        if (empty($name) || empty($email) || empty($password) || empty($passwordRepeat))
        {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        // Valid email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Check if passwords match
        if ($password !== $passwordRepeat)
        {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }

        // Check if email is already taken
        if ($this->userModel->emailExists($email))
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
}