<?php

namespace view;

require_once 'classes/view/htmldocument.php';

use controller\sessionController;

class contactPage extends \view\htmlDoc
{
    protected $userData = null;

    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Contact", $pages);
        $this->setPageHeaderText("Contact us");

        if (sessionController::isLoggedIn())
        {
            $this->userData = sessionController::getCurrentuser();
        }
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();

        $userName = isset($this->userData["name"]) ? htmlspecialchars($this->userData["name"]) : '';
        $userEmail = isset($this->userData["email"]) ? htmlspecialchars($this->userData["email"]) : '';
        
        echo '<div class="contact-form">'
            . PHP_EOL
            . '<form method="POST" action="index.php?page=contact&action=contact">'
            . PHP_EOL
            . '<input type="hidden" name="contact" value="1">'
            . PHP_EOL
            . '<div class="input-group">'
            . PHP_EOL
            . '<label for="name">Name:</label><br>'
            . PHP_EOL
            . '<input type="text" id="name" name="name" value="' . $userName . '" required><br>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL
            . '<div class="input-group">'
            . PHP_EOL
            . '<label for="email">Email:</label><br>'
            . PHP_EOL
            . '<input type="email" id="email" name="email" value = "' . $userEmail . '" required><br>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL
            . '<div class="input-group">'
            . PHP_EOL
            . '<label for="message">Message:</label><br>'
            . PHP_EOL
            . '<textarea id="message" name="message" required></textarea><br>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL
            . '<input type="submit" value="Send">'
            . PHP_EOL
            . '</form>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL;
    }
    
    // =====================================================================
}
