<?php

namespace App\controllers;

class ajaxController extends baseController
{
    protected string $function;

    // =============================================================================================
    protected function getData()
    {
        $this->function = $this->_getVar("function");
    }

    // =============================================================================================
    protected function processRequest() : bool
    {
        // Initialize with a getData call
        $this->getData();
        
        switch ($this->function)
        {
            case "populate":
                $code = file_get_contents("https://baconipsum.com/api/?type=all-meat&paras=1&start-with-lorem=1");

                $data = array();
                $data[] = array("target" => "div#top", "content" => "<h1>Multi Element Test</h1>");
                $data[] = array("target" => "div#mid", "content" => "<code>" . $code . "</code>");
                $data[] = array("target" => "div#bot", "content" => "<footer class='footer'>&copy;&nbsp;".date("Y")."&nbsp;Rens van Eck</footer>");
                
                // Send the response before returning
                $this->sendResponse($data);
                return true;

            case "reset":
                $data = array();
                $data[] = array("target" => "div#top", "content" => "Placeholder TOP");
                $data[] = array("target" => "div#mid", "content" => 'Placeholder MID<button id="populate">Populate divs</button>');
                $data[] = array("target" => "div#bot", "content" => "Placeholder BOT");
                
                // Send the response before returning
                $this->sendResponse($data);
                return true;

            default:
                // Return a properly formatted JSON error instead of echoing HTML
                $this->sendResponse([
                    "error" => true, 
                    "message" => "No action defined for function: " . $this->function
                ]);
                return false;
        }
    }
    
    // =============================================================================================
    protected function sendResponse($response) : void
    {
        header("Content-Type: application/json");
        echo json_encode($response);
    }
    
    // =============================================================================================
    protected function reportError(\Throwable $ex) : void
    {
        $this->sendResponse([
            "error" => true,
            "message" => $ex->getMessage(),
            "code" => $ex->getCode()
        ]);
    }
    // =============================================================================================
}