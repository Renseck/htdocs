<?php
namespace controller;

class sessionController 
{
    // =====================================================================
    public static function startSession() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    // =====================================================================
    public static function isLoggedIn() 
    {
        self::startSession();
        return isset($_SESSION["user"]);

    }
    // =====================================================================
    public static function logout()
    {
        self::startSession();
        session_unset();
        session_destroy();
    }
    // =====================================================================
}