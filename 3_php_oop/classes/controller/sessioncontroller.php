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
    public static function parseResult($result, $successPage, $errorPage)
    {
        if ($result["success"])
        {
            sessionController::setMessage("success", $result["message"]);
            
            if (!empty($successPage))
            {
                $_GET["page"] = $successPage;
            }
            
        } 
        else 
        {
            sessionController::setMessage("error", $result["message"]);
            if (!empty($errorPage))
            {
                $_GET["page"] = $errorPage;
            }
            
        }
    }
}
