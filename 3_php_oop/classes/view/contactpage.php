<?php

namespace view;

require_once 'classes/view/htmldocument.php';

class contactPage extends \view\htmlDoc
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Contact", $pages);
        $this->setPageHeaderText("Contact us");
    }
    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();
        echo '<div class="contact-form">'
            . PHP_EOL
            . '<form method="POST" acton="index.php?page=contact">'
            . PHP_EOL
            . '<input type="hidden" name="contact" value="1">'
            . PHP_EOL
            . '<div class="input-group">'
            . PHP_EOL
            . '<label for="name">Name:</label><br>'
            . PHP_EOL
            . '<input type="text" id="name" name="name" required><br>'
            . PHP_EOL
            . '</div>'
            . PHP_EOL
            . '<div class="input-group">'
            . PHP_EOL
            . '<label for="email">Email:</label><br>'
            . PHP_EOL
            . '<input type="email" id="email" name="email" required><br>'
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
