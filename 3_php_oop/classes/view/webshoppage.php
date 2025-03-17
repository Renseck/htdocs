<?php

namespace view;

require_once 'classes/view/htmldocument.php';

class webshopPage extends \view\htmlDoc
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Webshop", $pages);
        $this->setPageHeaderText("Webshop");
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();
        echo '<div class="maintext">'
            . PHP_EOL
            . '<p>Welcome to our webshop. This will start loading in all products from the database at some point!</p>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL;
    }
    
    // =====================================================================
}
