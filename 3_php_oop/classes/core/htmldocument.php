<?php
namespace core;

class htmlDoc {
	protected $title = "Default Title";
	protected $cssFiles = ["stylesheets/mystyle.css"];

	function __construct($title = null){
		if($title){
			$this->title = $title;
		}
	}
	
	function beginDoc() {
		echo "<!DOCTYPE html>\n<html>";
	}

	function beginHeader(){
		echo "<head>";
	}

	function headerContect() {
		echo "<title>$this->title</title>";
		foreach($this->cssFiles as $cssFile){
			echo "<link rel='stylesheet' type='text/css' href='$cssFile'>";
		}
	}

	function endHeader(){
		echo "</head>";
	}

	function beginBody(){
		echo "<body>";
	}

	function bodyContent(){
		echo "<h1>$this->title</h1>";
	}

	function endBody(){
		echo "</body>";
	}

	function endDoc(){
		echo "</html>";
	}

	function show(){
		$this->beginDoc();
		$this->beginHeader();
		$this->headerContect();
		$this->endHeader();
		$this->beginBody();
		$this->bodyContent();
		$this->endBody();
		$this->endDoc();
	}

	function addCSS($cssFile){
		$this->cssFiles[] = $cssFile;
	}
}