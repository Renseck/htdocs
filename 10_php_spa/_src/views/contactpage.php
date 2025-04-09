<?php

namespace App\views;

use App\factories\formfactory\formFactory;

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