<?php

namespace App\views\elements;

use App\views\elements\Element;
use App\factories\formfactory\formFactory;

class LoginForm extends Element
{
    public function getContent() : string
    {
        $formFactory = new formFactory();
        ob_start();
        
        $formFactory->createForm(
            page: 'login',
            action: 'index.php?page=login',
            method: 'POST',
            submit_caption: 'Login',
            attributes: ["class" => "ajax-form login-form"]
            );

        $formHtml = ob_get_clean();

        return $formHtml;
    }
}