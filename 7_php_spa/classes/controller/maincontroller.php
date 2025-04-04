<?php

namespace controller;

use config\pageConfig;
use controller\sessionController;
use factories\factoryManager;

class mainController
{
    private $pages;

    // =============================================================================================
    public function __construct()
    {
        $this->pages = pageConfig::getPages();
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest() : void
    {
        $requestData = $this->getRequestData();

        try
        {
            $controllerFactory = factoryManager::getInstance()->getFactory("controller");
            $controller = $controllerFactory->create($requestData["type"]);
            $controller->handleRequest($requestData["data"]);
        }
        catch (\Exception $e) 
        {
            sessionController::setMessage("error", "An error occurred: " . $e->getMessage());

            // Default to the home page
            $viewFactory = factoryManager::getInstance()->getFactory("view");
            $page = $viewFactory->create("home", [$this->pages]);
            $page->show();
        }

    }

    // =============================================================================================
    /**
     * Fetch the request data
     * @return array Contains 'type' and 'data' keys for further handling
     */
    private function getRequestData() : array
    {
        // Check if this is an API request
        if ($this->isApiRequest())
        {
            return [
                "type" => "api",
                "data" => [
                    "function" => $_GET["function"] ?? "all",
                    "type" => $_GET["type"] ?? "json",
                    "id" => $_GET["id"] ?? null,
                    "search" => $_GET["search"] ?? null
                ]
            ];
        }

        // AJAX request
        if ($this->isAjaxRequest())
        {
            $ajaxData = [
                "action" => $_GET["action"] ?? "",
                "id" => isset($_GET["id"]) ? (int)$_GET["id"] : null
            ];

            if ($_SERVER["REQUEST_METHOD"] === "POST")
            {
                if (isset($_POST["product_id"]))
                {
                    $ajaxData["product_id"] = (int)$_POST["product_id"];
                }

                if (isset($_POST["quantity"]))
                {
                    $ajaxData["quantity"] = (int)$_POST["quantity"];
                }

                if (isset($_POST["rating"]))
                {
                    $ajaxData["rating"] = (int)$_POST["rating"];
                }
            }

            return [
                "type" => "ajax",
                "data" => $ajaxData
                ];
        }

        // POST request
        if ($this->isPostRequest())
        {
            $postData = $_POST;

            // Ensure data is numeric
            if (isset($postData["product_id"])) 
            {
                $postData["product_id"] = (int)$postData["product_id"];
            }
            if (isset($postData["quantity"])) 
            {
                $postData["quantity"] = (int)$postData["quantity"];
            }

            return [
                "type" => "post",
                "data" => [
                    "page" => $_POST["page"] ?? "",
                    "formData" => $postData
                ]
            ];

        }

        // Default to GET request
        return [
            "type" => "get",
            "data" => [
                "page" => $_GET["page"] ?? "home",
                "id" => isset($_GET["id"]) ? (int)$_GET["id"] : null
            ]
        ];

    }

    // =============================================================================================
    /**
     * Check whether a request is an API request
     * @return bool
     */
    private function isApiRequest() : bool
    {
        return (isset($_GET["action"]) && $_GET["action"] === "api");
    }

    // =============================================================================================
    /**
     * Check whether a request is an AJAX request
     * @return bool
     */
    private function isAjaxRequest() : bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        || isset($_GET["is_ajax"])
        || isset($_POST["is_ajax"]);
    }

    // =============================================================================================
    private function isPostRequest() : bool
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }
}