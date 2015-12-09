<?php 
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );

function __autoload($class_name) {
    $pathArray = explode("\\", $class_name);

    $filePath = implode(DIRECTORY_SEPARATOR, $pathArray).'.php';
    if (file_exists($filePath))
        include $filePath;
    else{
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        echo "Page not found";
        die();
    }
}
