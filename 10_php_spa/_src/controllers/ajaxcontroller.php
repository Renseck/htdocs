<?php

namespace App\controllers;

use App\controllers\baseController;
use App\controllers\pageController;

class ajaxController extends baseController
{
    // =============================================================================================
    protected function processRequest(): bool
    {
        $function = $this->_getVar("function", $default = "NOTFOUND");

        switch ($function) 
        {
            case "page":
                // Delegate to pageController - buffer output and return it in json_encode
                try 
                {
                    ob_start();
                    $pageController = new pageController();
                    
                    // Set a flag to indicate we only want the body content for AJAX
                    $_GET['ajax'] = true;
                    
                    $success = $pageController->handleRequest();
                    $content = ob_get_clean();
                    
                    $this->sendResponse([
                        "error" => false,
                        "success" => $success,
                        "content" => $content
                    ]);
                    return true;

                } catch (\Throwable $ex) 
                {
                    ob_end_clean();
                    $this->reportError($ex);
                    return false;
                }

            default:
                $this->sendResponse([
                    "error" => true,
                    "message" => "No action defined for function [" . $function . "]"
                ]);
                return false;
        }
    }

    // =============================================================================================
    protected function reportError(\Throwable $ex): void
    {
        $this->sendResponse([
            "error" => true,
            "message" => $ex->getMessage(),
            "code" => $ex->getCode()
        ]);
    }

    // =============================================================================================
    protected function sendResponse($response) : void
    {
        header("Content-Type: application/json");
        echo json_encode($response);
    }
}