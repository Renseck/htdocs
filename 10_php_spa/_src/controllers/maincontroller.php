<?php

namespace App\controllers;

use App\controllers\sessionController;
use App\controllers\ajaxController;
use App\controllers\webController;

class mainController extends baseController
{
    public function __construct()
    {
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest()
    {
        $requestData = $this->getRequestData();

        switch($requestData["type"])
        {
            case "ajax":
                $ajaxController = new ajaxController();
                $ajaxController->handleRequest($requestData);
                break;

            case "api":
                $apiController = new apiController();
                $apiController->handleRequest($requestData);
                break;

            case "web":
                $webController = new webController();
                $webController->handleRequest($requestData);
                break;

        }
    }

    // =============================================================================================
    public function getRequestData() : array
    {
        $type = $this->determineRequestType();

        return [
            'type' => $type,
            'method' => $_SERVER["REQUEST_METHOD"],
            'data' => $_POST,
            'query' => $_GET
        ];
    }

    // =============================================================================================
    private function determineRequestType() : string
    {
        if ($this->isAjaxRequest())
        {
            return "ajax";
        } 

        if ($this->isApiRequest())
        {
            return "api";
        }
        else
        {
            return "web";
        }
    }

    // =============================================================================================
    private function isAjaxRequest() : bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // =============================================================================================
    private function isApiRequest() : bool
    {
        return (isset($_GET["action"]) && $_GET["action"] === "api");
    }
        
    // =============================================================================================
}