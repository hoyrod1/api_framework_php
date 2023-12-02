<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Page for Api Tasks Configurations
 * 
 * @category Tasks_Gateway_Configuration
 * @package  Class_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/TaskGateway.php
 */

// namespace Src\TaskController;

/**
 * Tasks Database Table Gateway Class
 * 
 * @category Task_Gateway
 * @package  Task_Gateway_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/TaskGateway.php
 */
class TasksGateway
{
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE RESOURCES=========*//
    private PDO $_conn;
    private string $_table_name = "tasks";
    private string $_id = "id";
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
     * @param int $users_id The all the resources associated with the id
     * 
     * @access public  
     * 
     * @return array
     */
    public function getAllForUser(int $users_id): array
    {
        $sql = "SELECT * FROM $this->_table_name 
                WHERE user_id = :users_id 
                ORDER BY $this->_id DESC";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":users_id", $users_id, PDO::PARAM_INT);
        $stmt->execute();
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
     * @param int    $user_id This is the user_id of the authenticated user
     * @param string $id      This is the id of a individual resource
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function getForUser(int $user_id, string $id)
    {
        $sql = "SELECT * FROM $this->_table_name 
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
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
     * @param int   $user_id This is the user_id of the authenticated user
     * @param array $data    This contains an array of data
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function createForUser(int $user_id, array $data): string
    {
        $sql = "INSERT INTO $this->_table_name(name, priority, is_completed, user_id)
                VALUES (:name, :priority, :is_completed, :user_id)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":name", $data['name'], PDO::PARAM_STR);
        // IF THE priority FIELD IS NOT SET BIND THE priority TO NULL VALUE
        if (empty($data['priority'])) {
            $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":priority", $data['priority'], PDO::PARAM_INT); 
        }
        $stmt->bindValue(":is_completed", $data['is_completed'] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->_conn->lastInsertId();

    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The update() FUNCTION UPDATES AN EXISTING RESOURCE 
     * 
     * @param int    $user_id This is the user_id of the authenticated user
     * @param string $id      This contains an id of the resource
     * @param array  $data    This contains an array of the resource
     * 
     * @access public  
     * 
     * @return int
     */
    public function updateForUser(int $user_id, string $id, array $data): int
    {
        $fields = [];
        if (!empty($data['name'])) {
            $fields['name'] = [
                $data['name'],
                PDO::PARAM_STR
            ];
        }
        if (array_key_exists("priority", $data)) {
            $fields['priority'] = [
              $data['priority'],
              $data['priority'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        }
        if (array_key_exists("is_completed", $data)) {
            $fields['is_completed'] = [
              $data['is_completed'],
              PDO::PARAM_BOOL
            ];
        }
        $set_Colunms = array_map(
            function ($value) {
                return "$value = :$value";
            }, array_keys($fields)
        );
        
        if (empty($set_Colunms)) {
            return 0;
        } else {

            $sql = "UPDATE $this->_table_name SET "
            . implode(", ", $set_Colunms)
            . " WHERE id = :id AND user_id = :user_id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            foreach ($fields as $name => $values) {

                $stmt->bindValue(":$name", $values[0], $values[1]);

            }
            // ===============BINDING VALUES THE LONG WAY======================= //
            // $stmt->bindValue(":name", $fields['name'][0], $fields['name'][1]);
            // $stmt->bindValue(":priority", $fields['priority'][0], $fields['priority'][1]);
            // $stmt->bindValue(":is_completed", $fields['is_completed'][0], $fields['is_completed'][1]);
            //*=========================================================================*//
            $stmt->execute();
            return $stmt->rowCount();
        }
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The delete() FUNCTION DELETES AN EXISTING RESOURCE 
     * 
     * @param int    $user_id This is the user_id of the authenticated user
     * @param string $id      The id of a individual resource
     * 
     * @access public  
     * 
     * @return int
     */
    public function deleteForUser(int $user_id,string $id): int
    {
        $sql = "DELETE FROM $this->_table_name 
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
    //*=========================================================================*//

}