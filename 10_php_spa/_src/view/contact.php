<?php

namespace view;

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
        
        $content = '
        <h1>Contact Us</h1>
        ' . $message . '
        <form id="contact-form" class="ajax-form" method="post" action="index.php?page=contact">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message"></textarea>
            </div>
            
            <button type="submit">Send Message</button>
        </form>';
        
        return $content;
    }

}