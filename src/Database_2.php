<?php
/**
 * * @file
 * php version 8.0
 * 
 * Page for Api Database Connection
 * 
 * @category Database
 * @package  Database_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/Database_2.php
 */

/**
 * Database class
 * 
 * @category Database
 * @package  Database_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/Database_2.php
 */
class Database
{
    //*=====BEGINNING OF CONSTRUCTOR FOR DATABASE PROPERTY ASSIGNMENT========*//
    /**
     * This constructor assigns the database values using Constructor promotion
     * The values is added when you add a visibility to the function aurgument 
     *
     * @param string $_servername 
     * @param string $_dbname 
     * @param string $_username 
     * @param string $_password 
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function __construct(
        private string $_servername,
        private string $_dbname,
        private string $_username,
        private string $_password
    ) {
    }
    //*========ENDING OF CONSTRUCTOR FOR DATABASE PROPERTY ASSIGNMENT===========*//

    //*=================BEGINNING OF DATABASE CONNECTION====================*//
    /**
     * This function connects to the database
     *   using PDO in PHP 8.0 configuration
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function connect(): PDO
    {
            $dsn = "mysql:host=$this->_servername;port=8888;dbname=$this->_dbname";
            $pdo_conn = new PDO(
                $dsn.";charset=utf8", $this->_username, $this->_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
                ]
            );
            return $pdo_conn;
    }
    //*===================ENDING OF DATABASE CONNECTION=====================*//
}