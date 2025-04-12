<?php

namespace App\controllers;

class apiController extends baseController
{
    // =============================================================================================
    public function handleRequest(array $requestData)
    {
        switch ($requestData['method'])
        {
            case "GET":
                $this->handleGetRequest($requestData);
                break;
            
            case "POST":
                $this->handlePostRequest($requestData);
                break;

            default:
                $this->jsonResponse(["success" => "false", "message" => "Method handling not implemented"], $status = 404);
                break;
        }
    }

    // =============================================================================================
    private function handleGetRequest(array $requestData)
    {

    }

    // =============================================================================================
    private function handlePostRequest(array $requestData)
    {

    }
}