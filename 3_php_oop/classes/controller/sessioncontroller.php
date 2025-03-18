<?php

namespace controller;

class sessionController
{
    // =============================================================================================
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =============================================================================================
    public static function isLoggedIn()
    {
        self::startSession();
        return isset($_SESSION["user"]);
    }

    // =============================================================================================
    public static function logout()
    {
        self::startSession();
        session_unset();
        session_destroy();
    }

    // =============================================================================================
    public static function getCurrentuser()
    {
        self::startSession();
        return $_SESSION["user"] ?? null;
    }

    // =============================================================================================
    public static function setMessage($type, $message) 
    {
        $_SESSION["messages"][$type] = $message;
    }
    
    // =============================================================================================
    public static function getMessages() 
    {
        $messages = $_SESSION["messages"] ?? [];

        // Clear messages after reading them to avoid undue persistence
        $_SESSION["messages"] = [];
        return $messages;
    }

    // =============================================================================================
}
