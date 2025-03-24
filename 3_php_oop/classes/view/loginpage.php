<?php

namespace view;

require_once 'classes/view/htmldocument.php';

class loginPage extends \view\htmlDoc 
{
    // =====================================================================
    public function __construct($pages)
    {
        parent::__construct("Login", $pages);
        $this->setPageHeaderText("Login");
    }

    // =====================================================================
    public function bodyContent()
    {
        parent::bodyContent();

        $formInfo = [
            "action" => "index.php",
            "page" => "login",
            "fields" => [
                ["type" => "email", "name" => "email", "required" => true],
                ["type" => "password", "name" => "password", "required" => true],
            ],
            "submitText" => "Login",
            "extraHtml" => '<p>Don\'t have an account? <a href="index.php?page=register">Register here</a></p>'
        ];

        $this->showForm($formInfo);
    }

    // =====================================================================
}