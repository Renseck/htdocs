<?php
namespace pages;
require_once 'classes/core/htmldocument.php';

class homePage extends \core\htmlDoc {
    function __construct(){
        parent::__construct("Home Page");
    }
    
    function bodyContent(){
        parent::bodyContent();
        echo '<div class="maintext">';
        echo '<p>Welcome to our site. This is the home page.</p>';
        echo '</div>';
    }

}