<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Page for Api Database Connection
 * 
 * @category Database
 * @package  Database_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks.com/src/Database.php
 */

/**
 * Database class
 * 
 * @category Database
 * @package  Database_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks.com/src/Database.php
 */
class Database
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE CONNECTION===========*//
    // WHEN USING TYPE DECLARATIONS PDO FOR THE private $_conn 
    // PREFIX THE PDO TYPE WITH A ? TO MAKE IT NULLABLE
    private ?PDO $_conn = null;

    // ONLY DECLARE THESE PRIVATE PROPERTIES FOR THE DATABASE CONNECTION IF
    // SCRIPT IS USING php version 7.4.33
    // ASSIGNING DATABASE VALUES IN CONTRUCTOR PARAMETER IS NOT SUPPORTED
    
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
        // THIS CHECKS IF $this->_conn IS NULL 
        // TO PREVENT MULTIPLE CALLS TO THE DATABASE CONNECTION 
        if ($this->_conn === null) {
        
            $dsn = "mysql:host=$this->_servername;dbname=$this->_dbname";
            
            $this->_conn = new PDO($dsn, $this->_username, $this->_password);
            
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //SET PDO::ATTR_EMULATE_PREPARES TO "false" WHEN RETRIEVING JSON DATA
            $this->_conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            //SET PDO::ATTR_STRINGIFY_FETCHES TO "false" WHEN RETRIEVING JSON DATA
            $this->_conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        
        }
        
        return $this->_conn;
    }
    //*===================ENDING OF DATABASE CONNECTION=====================*//
}