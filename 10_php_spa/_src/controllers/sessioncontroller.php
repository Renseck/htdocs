<?php

namespace App\controllers;

class sessionController extends baseController
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
}