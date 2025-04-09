<?php

namespace App\view;

class layout
{

    // =============================================================================================
    public static function render($content, $title = "My Website") {
        $html = '<!DOCTYPE html>
                <html>
                <head>
                    <title>' . $title . '</title>
                    <link rel="stylesheet" type="text/css" href="_src/stylesheets/mystyle.css">
                    <script src="_src/js/ajax.js"></script>
                </head>
                <body>
                <!-- Navigation container -->
                    <div id="nav-container">
                    ' . self::generateNavigation() . '
                    </div>
                    
                    <!-- Main content -->
                    <div id="main-content">
                        ' . $content . '
                    </div>
                    
                    <!-- Footer -->
                    <footer class="footer">
                        <p>Copyright © ' . date('Y') . ' Rens van Eck</p>
                    </footer>
                </body>
                </html>';

        return $html;
    }

    // =============================================================================================
    public static function generateNavigation() 
    {
        $currentPage = $_GET['page'] ?? 'home';
    
        $items = [
            'home' => 'HOME',
            'about' => 'ABOUT',
            'contact' => 'CONTACT'
        ];
        
        $menu = '<ul class="menu">';
        
        foreach ($items as $page => $label) {
            $activeClass = ($page === $currentPage) ? 'active' : '';
            $menu .= '<li><a href="index.php?page=' . $page . '" class="ajax-link ' . $activeClass . '">' . $label . '</a></li>';
        }
        
        $menu .= '</ul>';
        return $menu;
    }

}