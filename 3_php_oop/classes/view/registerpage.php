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

        $formInfo = [
            "action" => "index.php",
            "page" => "register",
            "fields" => [
                ["type" => "name", "name" => "name", "required" => true],
                ["type" => "email", "name" => "email", "required" => true],
                ["type" => "password", "name" => "password", "required" => true],
                ["type" => "password", "name" => "password_repeat", "label" => "Password repeat", "required" => true],
            ],
            "submitText" => "Register",
            "extraHtml" => '<p>Already have an account? <a href="index.php?page=login">Login here</a></p>'
        ];

        $this->showForm($formInfo);
    }
    
    // =====================================================================
}