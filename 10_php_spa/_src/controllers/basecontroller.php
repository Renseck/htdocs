<?php

namespace App\controllers;

class baseController
{
    // =============================================================================================
    protected function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // =============================================================================================
}