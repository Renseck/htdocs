<?php

namespace App\controllers;

use App\controllers\baseController;
use App\views\pages\BasePage;
use App\views\elements\ElementFactory;

class pageController extends baseController
{
    private $elementFactory;

    // =============================================================================================
    public function __construct()
    {
        $this->elementFactory = new ElementFactory();   
    }

    // =============================================================================================
    protected function processRequest(): bool
    {
        $page =  $this->_getVar("page", $default = "home");
        $isAjax = $this->_getVar("ajax", $default = false);

        $basePage = new basePage();
        
        $basePage->beginDoc();

        if ($isAjax)
        {
            $bodyContent = $this->getBodyContent($page);

            $basePage->addBodyElements($bodyContent);
            $basePage->showBody();
            return true;
        }

        $basePage->beginHeader();

        // ? How uhhh do we load specific files for specific pages like this
        $basePage->addCss("_src/assets/css/mystyle.css");
        $basePage->addJs("_src/assets/js/ajax.js");
        $headerContent = [$this->elementFactory->createElement("defaultHeader", true)];
        $basePage->addHeaderElements($headerContent);
        
        $basePage->showHeader();
        $basePage->endHeader();
        $basePage->beginBody();
        
        $bodyContent = $this->getBodyContent($page);

        $basePage->addBodyElements($bodyContent);
        $basePage->showBody();
        $basePage->showFooter();
        $basePage->endBody();
        $basePage->endDoc();
        return true;
    }

    // =============================================================================================
    protected function reportError(\Throwable $ex): void
    {
        echo "<div class='error'>";
        echo "<h2>An error occurred</h2>";
        echo "<p>" . htmlspecialchars($ex->getMessage()) . "</p>";
        echo "<p>Error code: " . $ex->getCode() . "</p>";
        echo "</div>";
    }

    // =============================================================================================
    protected function getBodyContent(string $page) : array
    {
        $bodyContent = [];
        $bodyContent[] = $this->elementFactory->createElement("navmenu", true);

        switch ($page)
        {
            case "home":
                $bodyContent[] = $this->elementFactory->createElement("homemsg", true);
                break;

            case "about":
                $bodyContent[] = null;
                break;

            case "contact":
                $bodyContent[] = $this->elementFactory->createElement("contactform", true);
                break;

            case "login":
                $bodyContent[] = $this->elementFactory->createElement("loginform", true);
                break;

            case "register":
                $bodyContent[] = $this->elementFactory->createElement("registerform", true);
                break;

            default:
            $bodyContent[] = $this->elementFactory->createElement("404", true);
                break;
        }

        return $bodyContent;
    }

}