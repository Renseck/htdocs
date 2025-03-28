<?php

namespace view;

require_once 'classes/view/htmldocument.php';

class homePage extends \view\htmlDoc
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Home", $pages);
    }
    
    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();
        echo '<div class="maintext">'
            . PHP_EOL
            . '<p>Welcome to our site. This is the home page.</p>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL;
    }

    // =====================================================================
}
