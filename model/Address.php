<?php
namespace Model
{
    use Core\Db;
            
    class Address
    {
        private $_headerStatus = 200;
        private $_errorMessage = "";

        function __construct()
        {
            $dataBase = new Db;
            $this->db = $dataBase->connect();
        }
        
        function getAddressesList()
        {
            $result = $this->db->query('SELECT * FROM address');
            if($data = $result->fetchAll()){
                return ["code" => 200, "data" => $data];
            }
            else{
                return ["code" => 409, "message" => "DataBase error"];
            }

        }
        
        function getAddressById($id)
        {
            $stmt = $this->db->prepare('SELECT * FROM address WHERE ADDRESSID = ?');
            if ($stmt->execute(array((int)$id))){
                $data = $stmt->fetchAll();
                if (empty($data[0]))
                    return ["code" => 404, "message" => "Can't find address"];
                else 
                    return ["code" => 200, "data" => $data[0]];
            }
            else{
                return ["code" => 409, "message" => "DataBase error"];
            }
        }


        function addAddress($inputData)
        {
            $preparedInputData = $this->_prepareInputData($inputData);
            if (!$preparedInputData)
                return ["code" => $this->_headerStatus, "message" => $this->_errorMessage];

            $stmt = $this->db->prepare('INSERT 
                INTO address(LABEL, STREET, HOUSENUMBER, POSTALCODE, CITY, COUNTRY) 
                values(:LABEL, :STREET, :HOUSENUMBER, :POSTALCODE, :CITY, :COUNTRY )
                ');

            if($stmt->execute($preparedInputData)){
                return ["code" => 201, "message" => "Address added"];
            }
            else{
                return ["code" => 409, "message" => "DataBase error"];
            }

        }

        function deleteAddressById($id)
        { 
            if ($id === FALSE){
                return ["code" => 405, "message" => "Method DELETE Not Allowed for this URI"];
            }

            $stmt = $this->db->prepare('DELETE FROM address WHERE ADDRESSID = ?');
            if ($stmt->execute(array((int)$id))){
                return ["code" => 204, "message" => "Address deleted"];
            }
            else{
                 return ["code" => 409, "message" => "DataBase error"];
            }
        }

        function updateAddressById($id, $inputData)
        {
            if ($id === FALSE){
                return ["code" => 405, "message" => "Method PUT Not Allowed for this URI"];
            }

            $preparedInputData = $this->_prepareInputData($inputData, $id);
            if (!$preparedInputData)
                return ["code" => $this->_headerStatus, "message" => $this->_errorMessage];

            $stmt = $this->db->prepare('UPDATE address 
                SET LABEL = :LABEL, STREET = :STREET, HOUSENUMBER = :HOUSENUMBER, POSTALCODE = :POSTALCODE, CITY = :CITY, COUNTRY = :COUNTRY 
                WHERE ADDRESSID = :ADDRESSID
                ');

            if($stmt->execute($preparedInputData)){
                return ["code" => 200, "message" => "Address updated"];
            }
            else{
                return ["code" => 409, "message" => "DataBase error"];
            }

        }

        //we can put here any validation logic
        private function _prepareInputData($inputData, $id = FALSE)
        {
            $requiredKeys = ["label", "street", "housenumber", "postalcode", "city", "country"];

            if(count(array_intersect_key(array_flip($requiredKeys), $inputData)) !== count($requiredKeys))
            {
                $this->_errorMessage = "All 6 fields(label, street, housenumber, postalcode, city, country) must be presented ".count($inputData);
                $this->_headerStatus = 400;

                return false;
            }

            $data = [
                ":LABEL" => "", 
                ":STREET" => "", 
                ":HOUSENUMBER" => "", 
                ":POSTALCODE" => "", 
                ":CITY" => "", 
                ":COUNTRY" => ""
                ];

            $data = [
                ":LABEL" => $inputData["label"], 
                ":STREET" => $inputData["street"], 
                ":HOUSENUMBER" => $inputData["housenumber"], 
                ":POSTALCODE" => $inputData["postalcode"], 
                ":CITY" => $inputData["city"], 
                ":COUNTRY" => $inputData["country"]
                ];

            if ($id !== FALSE)
                $data[":ADDRESSID"] = (int)$id;

            return $data;
        }

    }
}