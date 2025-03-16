<?php

namespace view;

require_once 'classes/view/htmldocument.php';

use controller\sessionController;

class cartPage extends \view\htmlDoc
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Shopping cart", $pages);
        $this->setPageHeaderText("Shopping cart");
    }
    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();
        echo '<p> this is going to show the contents of the shopping cart at some point'
            . PHP_EOL;
    }
}
