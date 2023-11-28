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

    //*===========THIS processRequest FUNCTION PROCESS THE URL REQUEST============*//
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


            if ($method == "GET") {
              
                $tasks = $this->_gateway->getAll();

                echo json_encode($tasks);

            } elseif ($method == "POST") {

                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->_getValidationError($data);
                if (!empty($errors)) {

                    $this->_responseUnprocessibleEntity($errors);
                    return;
                    
                }
                $return_id = $this->_gateway->create($data);
                $this->_responseResourceCreated($return_id);

            } else {

                $this->_responseMethodAllowed("GET, POST");
                return;

            }

        } else {

            $task = $this->_gateway->get($id);

            if ($task === false) {

                $this->_responseResourceNotFound($id);

            }

            switch ($method) {
            case 'GET':
                echo json_encode($task);
                break;

            case 'PATCH':

                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->_getValidationError($data, false);
                if (!empty($errors)) {

                    $this->_responseUnprocessibleEntity($errors);
                    return;
                    
                }
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
    //*===========================================================================*//
    
    //*PRIVATE _responseMethodAllowed FUNCTION CHECKS IF REQUEST METHOD IS ALLOWED*//
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
    //*===========================================================================*//

    //*PRIVATE _responseUnprocessibleEntity FUNCTION HANDLES UNPROCESSABLE RESPONSE//
    /**
     * This private method handles the response for unprocessable resources
     * 
     * @param array $errors The incorrect indvidual id
     * 
     * @access public  
     * 
     * @return void
     */
    private function _responseUnprocessibleEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }
    //*===========================================================================*//

    //*====PRIVATE _responseResourceNotFound FUNCTION CHECKS IF RESOURCE EXIST====*//
    /**
     * This private method handles the response for a indivual resource not found
     * 
     * @param string $idNotFound The incorrect indvidual id
     * 
     * @access public  
     * 
     * @return void
     */
    private function _responseResourceNotFound(string $idNotFound): void
    {
        http_response_code(404);
        echo json_encode(["message" => "The resource: $idNotFound not found"]);
    }
    //*===========================================================================*//

    //*= PRIVATE _responseResourceCreated FUNCTION CHECKS IF RESOURCE WAS CREATED=*//
    /**
     * This private method handles the response for a indivual resource not found
     * 
     * @param string $id The incorrect indvidual id
     * 
     * @access public  
     * 
     * @return void
     */
    private function _responseResourceCreated(string $id): void
    {
        http_response_code(201);
        echo json_encode(["message" => "Your task has been created", "id" => $id]);
    }
    //*===========================================================================*//

    //*=====PRIVATE _getValidationError FUNCTION CHECKS IF RESOURCE IS VALID======*//
    /**
     * This private method handles the validation of the created resource
     * 
     * @param array $data   This is an array of resources
     * @param bool  $is_new This parameter is set to true if creating new record
     * 
     * @access public  
     * 
     * @return array
     */
    private function _getValidationError(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data['name'])) {

            $errors[] = "The name is required";

        }

        if (! empty($data['priority'])) {
           
            $val_priority = filter_var($data['priority'], FILTER_VALIDATE_INT);
            if ($val_priority === false) {

                $errors[] = "Priority must be a integer";

            }
            
        }

        return $errors;
    }
    //*===========================================================================*//
}