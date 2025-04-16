<?php

namespace App\views\elements;

use App\views\elements\Element;
use App\factories\formfactory\formFactory;

class ContactForm extends Element
{
    public function getContent() : string
    {
        $formFactory = new formFactory();
        ob_start();
        
        $formFactory->createForm(
            page: 'contact',
            action: 'index.php?page=contact',
            method: 'POST',
            submit_caption: 'Send message',
            attributes: ["class" => "ajax-form contact-form"]
            );

        $formHtml = ob_get_clean();

        return $formHtml;
    }
}