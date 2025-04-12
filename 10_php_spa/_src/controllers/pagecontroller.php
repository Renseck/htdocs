<?php

namespace App\controllers;

use App\views\layout;
use App\views\homePage;
use App\views\aboutPage;
use App\views\contactPage;

class pageController extends baseController
{
    // =============================================================================================
    public function getContent(string $page) : string
    {
        $content = "";
        
        switch($page) {
            case "home":
                $homepage = new homePage();
                $content = $homepage->mainContent();
                break;
                
            case "about":
                $aboutpage = new aboutPage();
                $content = $aboutpage->mainContent();
                break;

            case "contact":
                $contactpage = new contactPage();
                $content = $contactpage->mainContent();
                break;

            default:
                $content = "<p>Page not found</p>";
        }
        
        return $content;
    }

    // =============================================================================================
    public function render(string $content) : void
    {
        echo layout::render($content);
    }
}