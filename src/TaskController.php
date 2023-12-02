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
 * @link     https://www.tasks/src/TaskController.php
 */

// namespace Src\TaskController;

/**
 * Controller class
 * 
 * @category Controller
 * @package  Controller_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/TaskController.php
 */
class TaskController
{
    //*=========PRIVATE PROPERTIES FOR DATABASE CONNECTION & USERS_ID==========*//
    private $_gateway;
    private $_users_id;
    //*=========================================================================*//

    //*========CONSTRUCTOR FOR TasksGateway OBJECT ASSIGNMENT & users ID========*//
    /**
     * This constructor takes in the TasksGateway object and users ID
     *
     * @param mixed $gateway 
     * @param int   $users_id 
     * 
     * @access public  
     * 
     * @return mixed
     */
    function __construct(TasksGateway $gateway, int $users_id)
    {
        $this->_gateway = $gateway;
        $this->_users_id = $users_id;
    }
    //*===========================================================================*//

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
              
                $tasks = $this->_gateway->getAllForUser($this->_users_id);

                echo json_encode($tasks);

            } elseif ($method == "POST") {
                // WHEN USING HTTPIE TO INCLUDE FORM DATA
                // ENTER KEY="Value string" KEY=VALUE_INTEGER
                // TO SEE THE VALUES IN HTTPIE ENTER --form AT THE END
                // TO RETRIEVE DATA INPUT FROM THE REQUEST BODY
                // USE file_get_contents FUNCTION AND THE php://input STREAM
                // PASS THE file_get_contents FUNCTION INTO json_decode FUNCTION
                // PASS IN true as 2nd AURGUMENT TO CONVERT TO ASSOCIATE ARRAY
                // TYPE CAST THE RETURN VALUE INTO AN (array) IF THERE IS NO DATA
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
            // INDIVIDUAL RESOURCE STORED IN THE $task VARIABLE USING THE $id
            $task = $this->_gateway->get($id);

            if ($task === false) {

                $this->_responseResourceNotFound($id);
                exit;
            }

            switch ($method) {
            case 'GET':
                echo json_encode($task);
                break;

            case 'PATCH':
                // WHEN USING HTTPIE TO INCLUDE FORM DATA
                // ENTER KEY="Value string" KEY=VALUE_INTEGER
                // TO SEE THE VALUES IN HTTPIE ENTER --form AT THE END
                // TO RETRIEVE DATA INPUT FROM THE REQUEST BODY
                // USE file_get_contents FUNCTION AND THE php://input STREAM
                // PASS THE file_get_contents FUNCTION INTO json_decode FUNCTION
                // PASS IN true as 2nd AURGUMENT TO CONVERT TO ASSOCIATE ARRAY
                // TYPE CAST THE RETURN VALUE INTO AN (array) IF THERE IS NO DATA
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->_getValidationError($data, false);
                if (!empty($errors)) {

                    $this->_responseUnprocessibleEntity($errors);
                    return;
                    
                }
                $updated_rows = $this->_gateway->update($id, $data);
                echo json_encode(
                    [
                      "message" => "Your tasks has been updates", 
                      "rows" =>  $updated_rows
                    ]
                );
                break;

            case 'DELETE':
                $deleted_row = $this->_gateway->delete($id);
                echo json_encode(
                    [
                      "message" => "Your tasks has been deleted", 
                      "rows" =>  $deleted_row
                    ]
                );
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
}