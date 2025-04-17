<?php

namespace App\views\elements;

use App\views\elements\Element;
use App\factories\formfactory\formFactory;

class RegistrationForm extends Element
{
    public function getContent() : string
    {
        $formFactory = new formFactory();
        ob_start();
        
        $formFactory->createForm(
            page: 'registration',
            action: 'index.php?page=register',
            method: 'POST',
            submit_caption: 'Register',
            attributes: ["class" => "ajax-form register-form"]
            );

        $formHtml = ob_get_clean();

        return $formHtml;
    }
}