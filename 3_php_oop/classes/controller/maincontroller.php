<?php

namespace controller;

use config\pageConfig;
use controller\sessionController;
use controller\authController;
use view\homePage;

class mainController
{
    private $pages;
    private $authController;

    // =============================================================================================
    public function __construct()
    {
        $this->pages = pageConfig::getPages();
        $this->authController = new authController();
        sessionController::startSession();
    }

    // =============================================================================================
    public function handleRequest()
    {
        // First, handle any POST actions (form submissions)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
            return; // POST handling includes redirection, so we don't continue to GET handling
        }
        
        // Then handle GET requests (page display)
        $this->handleGetRequest();
    }

    // =============================================================================================

    private function handlePostRequest()
    {
        // Determine which form was submitted based on URL parameters
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'login':
                $this->handleLogin();
                break;
                
            case 'register':
                $this->handleRegistration();
                break;
                
            case 'logout':
                $this->handleLogout();
                break;
                
            default:
                // Unknown action, redirect to home page
                header('Location: index.php?page=home');
                exit;
        }

    }

    // =============================================================================================

    private function handleGetRequest()
    {
        // Get the requested page from URL parameter, default to home
        $pageName = isset($_GET['page']) ? $_GET['page'] : 'home';
        
        // Check if the requested page is allowed
        if (!isset($this->pages[$pageName])) {
            $pageName = 'home';
        }
        
        // Get the class name for the requested page and show it
        $pageClass = $this->pages[$pageName];
        $page = new $pageClass($this->pages);
        $page->show();
    }

    // =============================================================================================

    private function handleLogin()
    {
        echo "<h1>Handling login</h1>";
    }
    // =============================================================================================

    private function handleRegistration()
    {

    }
    // =============================================================================================

    private function handleLogout()
    {

    }
    // =============================================================================================
}
