<?php
namespace Core
{
    class Router
    {
        function __construct()
        {
            $contentType = false;
            if(isset($_SERVER['CONTENT_TYPE'])) {
                $contentType = $_SERVER['CONTENT_TYPE'];
            }

            switch($contentType) {
                case "application/json":
                    $this->inputType = "json";
                    break;
                case "application/x-www-form-urlencoded":
                    $this->inputType = "urlencoded";
                    break;
                default:
                    $this->inputType = "urlencoded";
                    break;
            }

            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->requertUri = $_SERVER['REQUEST_URI'];
            $this->headerBody = file_get_contents("php://input");
        }


        function getRoute()
        {
            $data["controller"] =  "index";
            $data["action"] = "index";
            $data["inputData"] = [];
            $data["param"] = FALSE;

            //get controller
            $uri = explode("/", $this->requertUri);
            
            if (isset($uri[1]) and trim($uri[1]) != "")
                $data["controller"] = $uri[1];
            if (isset($uri[2]) and trim($uri[2]) != "")
                $data["param"] = $uri[2];

            //get action
            switch($this->method) {
                case "GET":
                    $data["action"] = "get";
                    break;
                case "POST":
                    $data["action"] = "insert";
                    break;
                case "PUT":
                    $data["action"] = "update";
                    break;
                case "DELETE":
                    $data["action"] = "delete";
                    break;
            }

            //get input data
            switch($this->inputType) {
                case "json":
                    $inputBody = json_decode($this->headerBody);
                    if($inputBody) {
                        foreach($inputBody as $name => $value) {
                            $data["inputData"][$name] = $value;
                        }
                    }
                    break;
                case "urlencoded":
                    parse_str($this->headerBody, $inputBody);
                    if($inputBody) {
                        foreach($inputBody as $name => $value) {
                            $data["inputData"][$name] = $value;
                        }
                    }
                    break;
            }
           
            return $data;
        }

    }
}