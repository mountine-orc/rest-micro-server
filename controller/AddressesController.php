<?php
namespace Controller;

use Core\View;
use Model\Address;

class AddressesController
{
    function __construct()
    {
        $this->address = new Address;
        $this->view = new View;
    }

    function getAction($id)
    {
        if (!$id)
            $result = $this->address->getAddressesList();
        else
            $result = $this->address->getAddressById($id);

        $this->_sendHeaderStatus($result["code"]);
        
        if (isset($result["data"]))
            $this->view->render($result["data"]);
        else 
            $this->view->render(["message" => $result["message"]]);
    }

    function insertAction($id = FALSE, $inputData)
    {
        if ($id !== FALSE){
            $this->_sendHeaderStatus(405);
            $this->view->render(["message" => "Method POST Not Allowed for this URI"]);
            return FALSE;
        }

        $result = $this->address->addAddress($inputData);

        $this->_sendHeaderStatus($result["code"]);
        $this->view->render(["message" => $result["message"]]);
    }

    function updateAction($id, $inputData)
    {
        $result = $this->address->updateAddressById($id, $inputData);

        $this->_sendHeaderStatus($result["code"]);
        $this->view->render(["message" => $result["message"]]);
    }

    function deleteAction($id)
    {
        $result = $this->address->deleteAddressById($id);

        $this->_sendHeaderStatus($result["code"]);
        $this->view->render(["message" => $result["message"]]);
    }

    private function _sendHeaderStatus($code, $link = FALSE)
    {
        switch ($code) {
            case 200:
                header('HTTP/1.1 200 Ok'); 
                break;
            case 201:
                header('HTTP/1.1 201 Created'); 
                if ($link)
                    header('Location: '.$link);
                break;                
            case 204:
                header('HTTP/1.1 204 No Content'); 
                break;                
            case 400:
                header('HTTP/1.1 400 Bad Request'); 
                break;
            case 404:
                header('HTTP/1.1 404 Not Found'); 
                break;
            case 405:
                header('HTTP/1.1 405 Method Not Allowed'); 
                break;
            case 409:
                header('HTTP/1.1 409 Conflict'); 
                break;
            default:
                # code...
                break;
        }
    }
}