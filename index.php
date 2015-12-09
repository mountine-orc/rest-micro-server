<?php
require_once("init.php");

use Core\Router;

$router = new Router;
$uriData = $router->getRoute(); 

$controllerName = 'Controller\\'.$uriData["controller"].'Controller';
$methodName = $uriData["action"]."Action";
$controller = new $controllerName();
$controller->$methodName($uriData["param"], $uriData["inputData"]);

