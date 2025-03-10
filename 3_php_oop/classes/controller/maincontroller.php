<?php
namespace controller;

use config\pageConfig;
use controller\sessionController;

class mainController 
{
    // =====================================================================
    private $pages;

    public function __construct() 
    {
        $this->pages = pageConfig::getPages();
    }
    // =====================================================================
    public function handleRequest()
    {
        sessionController::startSession();

        // 1. What is being requested?
        $pageKey = $_GET["page"] ?? "home";
        $pageClass = $this->pages[$pageKey] ?? homePage::class;

        // 2. Process request

        // 3. Show result
        $page = new $pageClass($this->pages);
        $page->show();
    }
    // =====================================================================
}