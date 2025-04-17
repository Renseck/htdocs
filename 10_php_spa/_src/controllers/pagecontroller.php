<?php

namespace App\controllers;

use App\controllers\baseController;
use App\views\pages\BasePage;
use App\views\elements\DefaultHeader;
use App\views\elements\WelcomeMessage;
use App\views\elements\LoginForm;
use App\views\elements\ContactForm;
use App\views\elements\NavMenu;
use App\views\elements\PageNotFound;
use App\views\elements\RegistrationForm;

class pageController extends baseController
{
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
        $headerContent = [new DefaultHeader(true)];
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
        $bodyContent[] = new NavMenu(true);

        switch ($page)
        {
            case "home":
                $bodyContent[] = new WelcomeMessage(true);
                break;

            case "about":
                $bodyContent[] = null;
                break;

            case "contact":
                $bodyContent[] = new ContactForm(true);
                break;

            case "login":
                $bodyContent[] = new LoginForm(true);
                break;

            case "register":
                $bodyContent[] = new RegistrationForm(true);
                break;

            default:
                $bodyContent[] = new PageNotFound(true);
                break;
        }

        return $bodyContent;
    }

}