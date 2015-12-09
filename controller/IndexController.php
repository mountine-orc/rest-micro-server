<?php
namespace Controller;

use Core\View;

class IndexController{
   function __construct()
    {
        $this->view = new View;
    }
    
	function getAction()
    {
        $this->view->render("index");
    }

    function __call($name, $arguments)
    {
        header('HTTP/1.1 405 Method Not Allowed'); 
        $this->view->render(["message" => "Method Not Allowed"]);
    }

}