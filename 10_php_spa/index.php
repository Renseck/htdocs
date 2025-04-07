<?php
echo '<!DOCTYPE html>';
echo '<link rel="stylesheet" type="text/css" href="stylesheets/mystyle.css">
     <script src="_src/js/ajax.js"></script>';

echo '<ul class="menu">
		<li><a href="index.php?page=home" class="ajax-link">HOME</a></li>
		<li><a href="index.php?page=about" class="ajax-link">ABOUT</a></li>
      </ul>

      <div id="content-container">';

$page = $_GET["page"] ?? "home";

switch($page)
{
	case "home":
		include "_src/view/home.php";
		$homepage = new homePage();
        $html = $homepage->mainContent();
        echo $html;
		break;
		
	case "about":
		include "_src/view/about.php";
		$aboutpage = new aboutPage();
        $html = $aboutpage->mainContent();
        echo $html;
		break;

}

echo '</div>
</body>
</html>';