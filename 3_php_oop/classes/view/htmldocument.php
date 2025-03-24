<?php

namespace view;

use controller\sessionController;
use controller\cartController;

class htmlDoc
{
	protected $title = "Default Title";
	protected $cssFiles = ["stylesheets/mystyle.css"];
	protected $pages = ["Home"];
	protected $pageHeaderText = "";
	private $cartController;

	// ================================================================================================
	protected function __construct($title = null, $pages = [])
	{
		if ($title) {
			$this->title = $title;
		}

		if ($pages) {
			$this->pages = $pages;
		}

		$this->cartController = new cartController();
	}

	// ================================================================================================
	protected function beginDoc()
	{
		echo '<!DOCTYPE html>'
			. PHP_EOL
			. '<html>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function beginHeader()
	{
		echo '<head>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function headerContent()
	{
		echo '<title>' . $this->title . '</title>'
			. PHP_EOL;
		foreach ($this->cssFiles as $cssFile) {
			echo '<link rel="stylesheet" type="text/css" href="' . $cssFile . '">'
				. PHP_EOL;
		}
	}

	// ================================================================================================
	protected function endHeader()
	{
		echo '</head>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function beginBody()
	{
		echo '<body>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function showMainHeader()
	{
		echo '<h1>Hello world!</h1>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function showPageSubheader()
	{
		echo '<h2 style="text-align:center;">' . $this->pageHeaderText . '</h2>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function bodyContent()
	{
		$this->showHyperlinkMenu();
		$this->displayMessages();
		$this->showPageSubheader();
	}

	// ================================================================================================
	protected function showHyperlinkMenu()
	{
		echo '<ul class="menu">'
			. PHP_EOL;
		foreach ($this->pages as $key => $class) {
			// Hide "Register" and "Login" if the user is logged in 
			if (sessionController::isLoggedIn() && in_array($key, ["register", "login"]))
			{
				continue;
			}

			// Hide "Logout" if the user is NOT logged in
			if (!sessionController::isLoggedIn() && in_array($key, ["logout", "cart"]))
			{
				continue;
			}

			// Don't show the confirmation page at all
			if (in_array($key, ["confirmation", "product"]))
			{
				continue;
			}

			// For logout, show the username in the buttton
			if ($key === "logout" && sessionController::isLoggedIn())
			{
				$currentUser = sessionController::getCurrentuser();
				$userName = htmlspecialchars($currentUser['name'] ?? 'User');
				$firstName = explode(" ", $userName)[0];
				echo '<li><a href="?page=' . $key . '">' . strtoupper($key) . ' [' . strtoupper($firstName) . ']</a></li>'
				. PHP_EOL;
			}
			elseif ($key === "cart" && sessionController::isLoggedIn())
			{
				$itemCount = $this->cartController->getItemCount();
				echo '<li><a href="?page=' . $key . '">' . strtoupper($key) . ' [' . number_format($itemCount) . ']</a></li>'
				. PHP_EOL;
			}
			else
			{
				echo '<li><a href="?page=' . $key . '">' . strtoupper($key) . '</a></li>'
				. PHP_EOL;
			}
			
		}
		echo '</ul>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function showFooter()
	{
		echo '<br>'
			. PHP_EOL
			. '<footer class="footer">&copy;&nbsp;' . date("Y") . '&nbsp;Rens van Eck</footer>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function endBody()
	{
		echo '</body>'
			. PHP_EOL;
	}

	// ================================================================================================
	protected function endDoc()
	{
		echo '</html>'
			. PHP_EOL;
	}

	// ================================================================================================
	public function addCSS($cssFile)
	{
		$this->cssFiles[] = $cssFile;
	}

	// ================================================================================================
	public function setPageHeaderText($text = "")
	{
		$this->pageHeaderText = $text;
	}

	// ================================================================================================
	public function displayMessages()
	{
		$messages = sessionController::getMessages();

		if (!empty($messages)) 
		{
			echo '<div class="messages">';
        	foreach ($messages as $type => $message) {
            	echo '<div class="message ' . htmlspecialchars($type) . '">';
            	echo htmlspecialchars($message);
            	echo '</div>';
        	}
        	echo '</div>';
		}
	}

	// ================================================================================================
	/**
	 * Generate forms generally
	 * @param array $formInfo Contains the information to generate the form
	 * 				MUST HAVE keys "action", "page", "fields", "submitText",
	 * 				MAY HAVE key "extraHtml"
	 * @param array FIELDS can have keys "type", "name",  "required", "value", ["id"], ["label"]
	 * 					labels enclosed in [] are generated when not specified
	 */
	protected function showForm(array $formInfo)
	{
		$this->openForm($formInfo["action"], $formInfo["page"]);
		$this->showFields($formInfo["fields"]);
		$this->closeForm($formInfo["submitText"], $formInfo["extraHtml"]);
	}

	// ================================================================================================

	protected function openForm(string $action, string $page)
	{
		echo '<div class="contact-form">' 
			. PHP_EOL
    		.'<form method="POST" action=' . $action . '>' 
			. PHP_EOL
			.'<input type="hidden" name="form_action" value="'. $page . '">'
			. PHP_EOL;
	}
	// ================================================================================================

	protected function showFields(array $fields)
	{
		foreach ($fields as $field)
		{
			$this->showField($field);				
		}
	}

	// ================================================================================================
	protected function showField(array $field)
{
    $type = $field["type"] ?? "text";
    $name = $field["name"] ?? "";
    $id = $field["id"] ?? $name;
    $label = $field["label"] ?? ucfirst($name);
    $required = isset($field["required"]) && $field["required"] ? "required" : "";
    $value = $field["value"] ?? "";

    echo '<div class="input-group">'
        . PHP_EOL
        . '<label for="' . $id . '">' . $label . ':</label><br>'
        . PHP_EOL;
        
    switch ($type) {
        case 'textarea':
            echo '<textarea id="' . $id . '" name="' . $name . '" ' . $required . '></textarea><br>'
                . PHP_EOL;
            break;

        default:
            echo '<input type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '" ' . $required . '><br>'
                . PHP_EOL;
    }
    
    echo '</div>' . PHP_EOL;
}

	// ================================================================================================
	protected function closeForm(string $submitText, string $extraHtml)
	{
		echo '<input type="submit" value="' . $submitText . '">' 
			. PHP_EOL
    		.'</form>' 
			. PHP_EOL;
    
    	if (!empty($extraHtml)) {
        	echo $extraHtml . PHP_EOL;
    	}
    
    	echo '</div>' . PHP_EOL;
	}
	// ================================================================================================

	// ================================================================================================
	public function show()
	{
		$this->beginDoc();
		$this->beginHeader();
		$this->headerContent();
		$this->endHeader();
		$this->beginBody();
		$this->showMainHeader();
		$this->bodyContent();
		$this->showFooter();
		$this->endBody();
		$this->endDoc();
	}
	
	// ================================================================================================
}
