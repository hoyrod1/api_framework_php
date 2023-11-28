<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Api Tasks
 * 
 * @category Tasks_Table_Gateway
 * @package  Curl_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */

// namespace Src\TaskController;

/**
 * Tasks Table Gateway Class
 * 
 * @category Task_Gateway
 * @package  Task_Gateway_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */
class TasksGateway
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE RESOURCES=========*//
    private PDO $_conn;
    private string $_table_name = "tasks";
    private string $_name = "name";
    private string $_priority = "priority";
    private string $_is_completed = "is_completed";
    private string $_date_time = "date_time	";
    //*===========ENDING OF PRIVATE PROPERTIES FOR DATABASE RESOURCES==========*//

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
     * The getAll() METHOD TO RETEIVE ALL DATA FROM THE TASKS TABLE 
     * 
     * @access public  
     * 
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM $this->_table_name ORDER BY $this->_name";
        $stmt = $this->_conn->query($sql);
        $data = [];
        while ($results = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results['is_completed'] = (bool) $results['is_completed'];
            $data[] = $results;
        }
        return $data;
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The get() METHOD TO RETEIVE ALL DATA FROM THE TASKS TABLE 
     * 
     * @param string $id The id of a individual resource
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function get(string $id)
    {
        $sql = "SELECT * FROM $this->_table_name WHERE id = :id";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($results !== false) {
            $results['is_completed'] = (bool) $results['is_completed'];
        }
        return $results;
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The create() FUNCTION CREATES A NEW RESOURCE 
     * 
     * @param array $data Thiis contains an array of data
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function create(array $data): string
    {
        $sql = "INSERT INTO $this->_table_name (name, priority, is_completed)
                VALUES (:name, :priority, :is_completed)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":name", $data['name'], PDO::PARAM_STR);
        // IF THE priority FIELD IS NOT SET BIND THE priority TO NULL VALUE
        if (empty($data['priority'])) {
            $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":priority", $data['priority'], PDO::PARAM_INT); 
        }
        $stmt->bindValue(":is_completed", $data['is_completed'] ?? false, PDO::PARAM_BOOL);
        $stmt->execute();
        return $this->_conn->lastInsertId();

    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The update() FUNCTION UPDATES A NEW RESOURCE 
     * 
     * @param string $id   This contains an id of the resource
     * @param array  $data This contains an array of data
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function update(string $id, array $data): string
    {
        $fields = [];
        if (!empty($data['name'])) {
            $fields['name'] = [
                $data['name'],
                PDO::PARAM_STR
            ];
        }
        $sql = "UPDATE $this->_table_name SET 
                name = :name, 
                priority = :priority, 
                is_completed = :is_completed
                WHERE id = :id";
        $stmt = $this->_conn->prepare($sql);

    }
    //*=========================================================================*//

}