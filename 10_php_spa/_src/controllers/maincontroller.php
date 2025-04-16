<?php

namespace App\controllers;

use App\interfaces\iController;

class mainController implements iController
{
    // =============================================================================================
    public function handleRequest(): bool
    {
        $action = $this->_getVar("action", $default = "page");

        switch ($action)
        {
            case "ajax":
                $controller = new ajaxController();
                return $controller->handleRequest();

            case "page":
                $controller = new pageController();
                return $controller->handleRequest();

            default:
                return false;
        }
    }

    // =============================================================================================
    protected function _getVar($name, $default = "NOTFOUND") : string
    {
        return $_GET[$name] ?? $default;
    }
}