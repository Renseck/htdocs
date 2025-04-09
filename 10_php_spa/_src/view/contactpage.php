<?php

namespace App\view;

use App\factories\formfactory\factory\formFactory;

class contactPage
{
    public function mainContent() {
        // Check for form submission result to show success/error message
        $message = '';
        if (isset($_SESSION['contact_result'])) {
            $result = $_SESSION['contact_result'];
            $message = '<div class="' . ($result['success'] ? 'success' : 'error') . '">' . 
                      $result['message'] . '</div>';
            
            // Clear the session message after displaying it
            unset($_SESSION['contact_result']);
        }
        
        $content = '<h1>Contact Us</h1>' . $message;

        $formFactory = new formFactory();
        $contactForm = $formFactory->create(formFactory::TYPE_CONTACT);
        $content .= $contactForm->render();
        
        return $content;
    }

}