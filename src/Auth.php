<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Api Authentication Configurations
 * 
 * @category Users_Authentication_Configuration
 * @package  Authentication_Class_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/Auth.php
 */

/**
 * Authentication Class
 * 
 * @category Authentication
 * @package  Users_Authentication_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/Auth.php
 */

class Auth
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR UsersGateway Class=========*//
    private $_usersGateway;
    //*===========ENDING OF PRIVATE PROPERTIES FOR UsersGateway Class==========*//

    //*========BEGINNING OF CONSTRUCTOR FOR UsersGateway OBJECT ASSIGNMENT========*//
    /**
     * This constructor takes in the TasksGateway object
     *
     * @param mixed $usersGateway 
     * 
     * @access public  
     * 
     * @return mixed
     */
    function __construct(UsersGateway $usersGateway)
    {
        $this->_usersGateway = $usersGateway;
    }
    //*==========ENDING OF CONSTRUCTOR FOR UsersGateway OBJECT ASSIGNMENT=========*//

    //*=========================================================================*//
    /**
     * The authenticateAPIKey() method authenticates a valid API key
     * 
     * @access public  
     * 
     * @return bool
     */
    public function authenticateAPIKey(): bool
    {
        $api_key = $_SERVER["HTTP_X_API_KEY"];
        $new_user = $this->_usersGateway->getByAPIKey($api_key);

        if (empty($api_key)) {
            
            http_response_code(404);
            echo json_encode(["message" => "missing API key"]);
            return false;
        }
        
        if ($new_user === false) {

            http_response_code(401);
            echo json_encode(["message" => "Request unauthorized: Invalid APIKey"]);
            return false;
        }

        return true;
    }
    //*===========================================================================*//
}