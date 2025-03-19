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
			if ($key === "confirmation")
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
