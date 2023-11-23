<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Error Handling
 * 
 * @category Error_Handler
 * @package  Curl_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */

 /**
  * ErrorHandler class
  * 
  * @category ErrorHandler
  * @package  ErrorHandler_Class
  * @author   Rodney St.Cloud <hoyrod1@aol.com>
  * @license  STC Media inc
  * @link     https://www.api-todolist.com
  */

class ErrorHandler
{
    /**
     * This class handles the excepetions
     * 
     * @param mixed $exception This is an Object of the throwable Class
     * 
     * @access public  
     * 
     * @return void
     */
    public static function handleException(Throwable $exception):void
    {
        http_response_code(500);
        
        echo json_encode(
            [
              "code" => $exception->getCode(),
              "message" => $exception->getMessage(),
              "file" => $exception->getFile(),
              "line" => $exception->getLine()
            ]
        );
    }
}