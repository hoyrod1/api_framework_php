<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Page for Api Users Configurations
 * 
 * @category Users_Gateway_Configuration
 * @package  Class_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/UsersGateway.php
 */

/**
 * Tasks Table Gateway Class
 * 
 * @category Task_Gateway
 * @package  Task_Gateway_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/TaskGateway.php
 */
Class UsersGateway
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE RESOURCES=========*//
    private PDO $_conn;
    private string $_table_name = "users";
    private string $_id = "id";
    private string $_name = "name";
    private string $_username = "username";
    private string $_password_hash = "password_hash";
    private string $_api_key = "api_key	";
    //*===========ENDING OF PRIVATE PROPERTIES FOR DATABASE RESOURCES===========*//

    //*========BEGINNING OF CONSTRUCTOR FOR DATABASE OBJECT ASSIGNMENT==========*//
    /**
     * This constructor takes in a database object
     * Then assigns it to the $conn property
     *
     * @param mixed $database 
     * 
     * @access public  
     * 
     * @return mixed
     */
    function __construct(Database $database)
    {
        $this->_conn = $database->connect();
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The create() FUNCTION CREATES A NEW RESOURCE 
     * 
     * @param string $name          This contains the registeres name
     * @param string $username      This contains the registeres username
     * @param string $password_hash This contains the registeres hashed password
     * @param string $api_key       This contains the registeres random api-key
     * 
     * @access public  
     * 
     * @return string
     */
    public function createUser(
        string $name, 
        string $username, 
        string $password_hash, 
        string $api_key
    ): string {
        $sql = "INSERT INTO $this->_table_name 
               (name, username, password_hash, api_key) 
               VALUES (:name, :username, :password_hash, :api_key)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);
        $stmt->bindValue(":api_key", $api_key, PDO::PARAM_STR);
        $stmt->execute();
        return $this->_conn->lastInsertId();

    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The getByAPIKey() METHOD TO RETEIVES AUTHENTICATED DATA FROM THE USERS TABLE 
     * 
     * @param string $key This has the API Key
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function getByAPIKey(string $key)
    {
        $sql = "SELECT * FROM $this->_table_name WHERE api_key = :api_key";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":api_key", $key, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The getUserByUserName() METHOD LOGGS IN THE USER 
     * 
     * @param string $username This has the users username
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function getUserByUserName(string $username)
    {
        $sql = "SELECT * FROM $this->_table_name WHERE username = :username";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The getUserByRefreshTokenId() METHOD RETRIEVES THE USER DATA
     * 
     * @param int $id This has the users id
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function getUserByRefreshTokenId(int $id)
    {
        $sql = "SELECT * FROM $this->_table_name WHERE id = :id";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
    //*=========================================================================*//


}