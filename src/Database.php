<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Api Database Connection
 * 
 * @category Database
 * @package  Database_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */

/**
 * Database class
 * 
 * @category Database
 * @package  Database_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */
class Database
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE CONNECTION===========*//
    // ONLY DECLARE THESE PRIVATE PROPERTIES FOR THE DATABASE CONNECTION IF
    //THE CONSTRUCTOR IS PERFORMING ANOTHER TASK OTHER THAN ASSIGNING DATABASE VALUES
    private $_servername;
    private $_username;
    private $_password;
    private $_dbname;
    //*===========ENDING OF PRIVATE PROPERTIES FOR DATABASE CONNECTION============*//

    //*=====BEGINNING OF CONSTRUCTOR FOR DATABASE PROPERTY ASSIGNMENT========*//
    /**
     * This constructor assigns the database values using Constructor promotion
     * The values is added when you add a visibility to the function aurgument 
     *
     * @param string $_servername 
     * @param string $_username 
     * @param string $_password 
     * @param string $_dbname 
     * 
     * @access public  
     * 
     * @return mixed
     */
    function __construct($_servername, $_username, $_password, $_dbname)
    {
        $this->_servername = $_servername;
        $this->_username = $_username;
        $this->_password = $_password;
        $this->_dbname = $_dbname;
    }
    //*========ENDING OF CONSTRUCTOR FOR DATABASE PROPERTY ASSIGNMENT===========*//

    //*=================BEGINNING OF DATABASE CONNECTION====================*//
    /**
     * This function connects to the database using PDO  
     * 
     * @access public  
     * 
     * @return null
     */
    public function connect(): PDO
    {
        $dsn = "mysql:host=$this->_servername;dbname=$this->_dbname";
        $pdo_conn = new PDO($dsn, $this->_username, $this->_password);
        
        $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo_conn;
    }
    //*===================ENDING OF DATABASE CONNECTION=====================*//
}