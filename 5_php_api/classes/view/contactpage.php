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
        
        $formInfo = [
            "action" => "index.php",
            "page" => "contact",
            "fields" => [
                ["type" => "text", "name" => "name", "value" => $userName, "required" => true],
                ["type" => "email", "name" => "email", "value" => $userEmail, "required" => true],
                ["type" => "textarea", "name" => "message", "required" => true]
            ],
            "submitText" => "Send",
            "extraHtml" => ""
        ];

        $this->showForm($formInfo);

    }
    
    // =====================================================================
}
