<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Page for Error Handling
 * 
 * @category Error_Handler
 * @package  Error_Handle_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/ErrorHandler.php
 */

 /**
  * ErrorHandler class
  * 
  * @category ErrorHandler
  * @package  ErrorHandler_Class
  * @author   Rodney St.Cloud <hoyrod1@aol.com>
  * @license  STC Media inc
  * @link     https://www.tasks/src/ErrorHandler.php
  */

class ErrorHandler
{
    //*================THE PUBLIC STATIC FUNCTION handleErrors()===============*//
    /**
     * This class handles the errors
     * 
     * @param int    $errno   This is an Object of the throwable Class
     * @param string $errstr  This is an Object of the throwable Class
     * @param string $errfile This is an Object of the throwable Class
     * @param int    $errline This is an Object of the throwable Class
     * 
     * @access public  
     * 
     * @return void
     */
    public static function handleErrors(
        int $errno, 
        string $errstr, 
        string $errfile, 
        int $errline
    ):void {

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);

    }
    //*===========================================================================*//

    //*================THE PUBLIC STATIC FUNCTION handleException()===============*//
    /**
     * This static function handles the excepetions errors
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
    //*===========================================================================*//
}