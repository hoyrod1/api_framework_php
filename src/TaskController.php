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
    /**
     * Proccesses the url request whether there is an id or no id
     * If there is no id  the type decloration is prefixed by a ? 
     * to declare it as NULLABLE  
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
                echo "This is the index page is";
            } elseif ($method == "POST") {
                echo "This is the create page";
            } else {
                $this->_responseMethodAllowed("GET, POST");
            }

        } else {

            switch ($method) {
            case 'GET':
                echo "This is a single page for $id";
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
}