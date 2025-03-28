<?php

namespace controller;

class sessionController
{
    private static $initialized = false;

    // =============================================================================================
    public static function startSession()
    {
        if (self::$initialized)
        {
            return;
        }

        if (!headers_sent() && session_status() === PHP_SESSION_NONE)
        {
            session_start();
            self::$initialized = true;
        }
        elseif (session_status() === PHP_SESSION_ACTIVE)
        {
            self::$initialized = true;
        }
    }

    // =============================================================================================
    public static function isLoggedIn() : bool
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
    public static function getCurrentuser() : array
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
    public static function getMessages()  : array
    {
        $messages = $_SESSION["messages"] ?? [];

        // Clear messages after reading them to avoid undue persistence
        $_SESSION["messages"] = [];
        return $messages;
    }

    // =============================================================================================
    public static function parseResult($result, $successPage = null, $errorPage = null)
    {
        if ($result["success"]) {
            self::setMessage("success", $result["message"]);
            if ($successPage !== null) {
                $_GET["page"] = $successPage;
            }
        } else {
            self::setMessage("error", $result["message"]);
            if ($errorPage !== null) {
                $_GET["page"] = $errorPage;
            }
        }

    }
    
}
