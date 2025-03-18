<?php

namespace view;

require_once 'classes/view/htmldocument.php';

class registerPage extends \view\htmlDoc 
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Register", $pages);
        $this->setPageHeaderText("Register");
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();
        echo '<div class="contact-form">'
            .PHP_EOL
                .'<form method="POST" action="index.php?page=register&action=register">'
                .PHP_EOL
                    .'<div class="input-group">'
                    .PHP_EOL
                        .'<label for="name">Name:</label><br>'
                        .PHP_EOL
                        .'<input type="name" id="name" name="name" required><br>'
                        .PHP_EOL
                    .'</div>'
                    .PHP_EOL
                    .'<div class="input-group">'
                    .PHP_EOL
                        .'<label for="email">Email:</label><br>'
                        .PHP_EOL
                        .'<input type="email" id="email" name="email" required><br>'
                        .PHP_EOL
                    .'</div>'
                    .PHP_EOL
                    .'<div class="input-group">'
                    .PHP_EOL
                        .'<label for="password">Password:</label><br>'
                        .PHP_EOL
                        .'<input type="password" id="password" name="password" required><br>'
                        .PHP_EOL
                    .'</div>'
                    .PHP_EOL
                    .'<div class="input-group">'
                    .PHP_EOL
                        .'<label for="password">Password repeat:</label><br>'
                        .PHP_EOL
                        .'<input type="password" id="password" name="password_repeat" required><br>'
                        .PHP_EOL
                    .'</div>'
                    .PHP_EOL
                    .'<input type="submit" value="Register">'
                    .PHP_EOL
                .'</form>'
                .PHP_EOL
            .'</div>'
            .PHP_EOL;
    }
    
    // =====================================================================
}