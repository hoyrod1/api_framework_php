<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Api Controller
 * 
 * @category Controller
 * @package  Curl_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */

// namespace Src\TaskController;

/**
 * Controller class
 * 
 * @category Controller
 * @package  Controller_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */
class TaskController
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE CONNECTION=========*//
    private $_gateway;
    //*===========ENDING OF PRIVATE PROPERTIES FOR DATABASE CONNECTION==========*//

    //*========BEGINNING OF CONSTRUCTOR FOR TasksGateway OBJECT ASSIGNMENT========*//
    /**
     * This constructor takes in the TasksGateway object
     *
     * @param mixed $gateway 
     * 
     * @access public  
     * 
     * @return mixed
     */
    function __construct(TasksGateway $gateway)
    {
        $this->_gateway = $gateway;
    }
    //*==========ENDING OF CONSTRUCTOR FOR TasksGateway OBJECT ASSIGNMENT=========*//

    /**
     * Proccesses the url request whether there is an id or no id
     * If there is no id given the id type decloration
     * is prefixed by a '?' to declare it as NULLABLE
     *
     * @param string $method 
     * @param string $id 
     * 
     * @access public  
     * 
     * @return void
     */
    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null) {

            $tasks = $this->_gateway->getAll();

            if ($method == "GET") {

                echo json_encode($tasks);

            } elseif ($method == "POST") {

                echo "This is the create page";

            } else {

                $this->_responseMethodAllowed("GET, POST");
                return;

            }

        } else {

            $task = $this->_gateway->get($id);

            if ($task === false) {

                $this->_responseMethodNotFound($id);

            }

            switch ($method) {
            case 'GET':
                echo json_encode($task);
                break;

            case 'PATCH':
                echo "This is the update page for $id";
                break;

            case 'DELETE':
                echo "This is the delete page for $id";
                break;
                
            default:
                $this->_responseMethodAllowed("GET, POST");
            }

        }

    }
    /**
     * This private method handles the response methods allowed
     * 
     * @param string $methodsAllowed 
     * 
     * @access public  
     * 
     * @return void
     */
    private function _responseMethodAllowed(string $methodsAllowed): void
    {
        http_response_code(405);
        header("Allow: $methodsAllowed");
    }
    /**
     * This private method handles the response methods not found
     * 
     * @param string $idNotFound The incorrect indvidual id
     * 
     * @access public  
     * 
     * @return void
     */
    private function _responseMethodNotFound(string $idNotFound): void
    {
        http_response_code(404);
        echo json_encode(["message" => "The resource: $idNotFound not found"]);
    }
}