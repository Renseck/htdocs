<?php

namespace controller;

use config\pageConfig;
use factories\factoryManager;

class getController
{
    private $pages;

    // =============================================================================================
    public function __construct()
    {
        $this->pages = pageConfig::getPages();
    }

    // =============================================================================================
    public function handleRequest(array $data) : void
    {
        $pageName = $data["page"] ?? "home";

        try 
        {
            $viewFactory = factoryManager::getInstance()->getFactory("view");
            $page = $viewFactory->create($pageName, [$this->pages]);
            $page->show();
        }
        catch (\InvalidArgumentException $e)
        {
            sessionController::setMessage("error", "Page not found");
            $page = $viewFactory->create("home", [$this->pages]);
            $page->show();
        }
    }
}