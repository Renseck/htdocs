<?php

namespace utils;

class validator
{
    public static function validateEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // =============================================================================================
    public static function validatePasswordMatch(string $password, string $confirm)
    {
        return $password === $confirm;
    }

    // =============================================================================================
    public static function validateRequired(array $fields)
    {
        foreach ($fields as $key => $value)
        {
            if (empty($value))
            {
                return false;
            }
        }
        return true;
    }

}