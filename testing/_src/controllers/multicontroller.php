<?php
namespace App\controllers;

use App\interfaces\iController;

class multiController implements iController
{
    protected $_isAjax;
    // =============================================================================================

    public function __construct()
    {
        $this->_isAjax = $this->_getVar("action") === "ajax";
    }
    // =============================================================================================

    public function handleRequest() : bool
    {
        if ($this->_isAjax)
        {
            $controller = new ajaxController();
        }
        else
        {
            $controller = new pageController();
        }
        return $controller->handleRequest();
    }

    // =============================================================================================
    
    protected function _getVar($name, $default = "NOTFOUND") : string
    {
        return $_GET[$name] ?? $default;
    }
}