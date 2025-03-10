<?php
namespace view;
require_once 'classes/view/htmldocument.php';
use controller\sessionController;

class logoutPage extends \view\htmlDoc 
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Logout", $pages);
    }
    // =====================================================================
    public function bodyContent() 
    {
        sessionController::logout();
        parent::bodyContent();
        echo '<p>You have been logged out.</p>'
            .PHP_EOL;
    }
    // =====================================================================
}