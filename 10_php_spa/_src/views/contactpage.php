<?php

namespace App\views;

use App\factories\formfactory\formFactory;

class contactPage
{
    public function mainContent() {
        $content = '<h1>Contact Us</h1>';

        // Using the formFactory with proper parameters
        $formFactory = new formFactory();
        // Use createForm and capture the output with output buffering
        ob_start();
        
        $formFactory->createForm(
            page: 'contact',
            action: 'index.php?page=contact',
            method: 'POST',
            submit_caption: 'Send Message',
            attributes: ["class" => "ajax-form contact-form"]
            );

        $formHtml = ob_get_clean();
        
        $content .= $formHtml;
        
        return $content;
    }
}