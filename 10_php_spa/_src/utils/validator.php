<?php

namespace App\utils;

class validator
{
    private static $errors = [];

    // =============================================================================================
    /**
     * Validates if all required fields are present and valid
     * 
     * @param array $fields Array containing the form data
     * @param array $required Array containing required field names with optional validation types
     * @return bool True if validation passes, false otherwise
     */
    public static function validateRequired(array $fields, array $required) : bool
    {
        $errors = [];
        
        foreach ($required as $fieldName => $validation) {
            // If it's a simple required field with no specific validation
            if (is_int($fieldName) && is_string($validation)) {
                $fieldName = $validation;
                $validation = 'required';
            }
            
            // Check if field exists and is not empty
            if ($validation === 'required' || str_contains($validation, 'required')) {
                if (empty($fields[$fieldName])) {
                    $errors[] = ucfirst($fieldName) . " is required";
                    continue;
                }
            }
            
            // Additional validation for specific types
            if (!empty($fields[$fieldName])) {
                if ($validation === 'email' || str_contains($validation, 'email')) {
                    if (!filter_var($fields[$fieldName], FILTER_VALIDATE_EMAIL)) {
                        $errors[] = ucfirst($fieldName) . " is invalid";
                    }
                }
            }
        }
        
        // Store errors in a static property that can be retrieved later
        self::$errors = $errors;
        
        return empty($errors);
    }
    
    // =============================================================================================
    /**
     * Get validation errors
     * 
     * @return array Array of validation error messages
     */
    public static function getErrors() : array
    {
        return self::$errors;
    }
}