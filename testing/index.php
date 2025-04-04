<?php
// Handle AJAX requests
if (isset($_GET['is_ajax']) || isset($_POST['is_ajax'])) {
    $page = $_GET['page'] ?? 'home';
    
    // Return JSON response based on requested page
    header('Content-Type: application/json');
    
    switch ($page) {
        case 'home':
            echo json_encode([
                'targets' => [
                    '#content' => '<h1>Welcome Home</h1><p>This is the home page content loaded via AJAX.</p>',
                    '#page-title' => 'Home'
                ],
                'scripts' => [
                    'console.log("Home page loaded");'
                ],
                'notifications' => [
                    ['type' => 'success', 'message' => 'Home page loaded successfully!']
                ]
            ]);
            break;
            
        case 'about':
            echo json_encode([
                'targets' => [
                    '#content' => '<h1>About Us</h1><p>This is the about page content loaded via AJAX.</p>',
                    '#page-title' => 'About Us'
                ],
                'notifications' => [
                    ['type' => 'info', 'message' => 'About page loaded!']
                ]
            ]);
            break;
            
        case 'contact':
            echo json_encode([
                'targets' => [
                    '#content' => '<h1>Contact Us</h1>
                        <form action="index.php?page=submit-form" method="post">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea id="message" name="message" required></textarea>
                            </div>
                            <button type="submit">Send Message</button>
                        </form>',
                    '#page-title' => 'Contact Us'
                ]
            ]);
            break;
            
        case 'submit-form':
            // Process form submission
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            
            echo json_encode([
                'targets' => [
                    '#content' => "<h1>Thank You!</h1><p>Hello $name, we've received your message and will respond to $email soon.</p>",
                    '#page-title' => 'Form Submitted'
                ],
                'notifications' => [
                    ['type' => 'success', 'message' => 'Form submitted successfully!']
                ]
            ]);
            break;
            
        default:
            echo json_encode([
                'targets' => [
                    '#content' => '<h1>Page Not Found</h1><p>The requested page was not found.</p>',
                    '#page-title' => '404 - Not Found'
                ],
                'notifications' => [
                    ['type' => 'error', 'message' => 'Page not found!']
                ]
            ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><span id="page-title">Home</span> | SPA Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        nav {
            background: #f4f4f4;
            padding: 10px;
            margin-bottom: 20px;
        }
        nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #333;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
        }
        button {
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        #notifications {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .notification {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            color: white;
            width: 300px;
        }
        .success {
            background-color: #4CAF50;
        }
        .error {
            background-color: #f44336;
        }
        .info {
            background-color: #2196F3;
        }
        #app-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="app-loader">
        <div class="loader"></div>
    </div>
    
    <div id="notifications"></div>
    
    <nav>
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=about">About</a>
        <a href="index.php?page=contact">Contact</a>
    </nav>
    
    <main id="content">
        <h1>Welcome Home</h1>
        <p>This is the initial content that will be replaced dynamically.</p>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="spa.js"></script>
</body>
</html>