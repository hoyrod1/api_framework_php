<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Page for Api Refresh Token Database Table
 * 
 * @category Refresh_Token_Table_Configuration
 * @package  Class_Table_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/RefreshTokenGateway.php
 */

/**
 * Refresh Token Table Gateway Class
 * 
 * @category Refresh_Token_Gateway
 * @package  Refresh_Token_Gateway_Class
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/RefreshTokenGateway.php
 */
class RefreshTokenGateway
{
    
    //*=========BEGINNING OF PRIVATE PROPERTIES FOR DATABASE RESOURCES=========*//
    private PDO $_conn;
    private string $_table_name = "refresh_token";
    private string $_key;
    private string $_hash_token;
    private int $_expires_at;
    //*=====================================================================*//


    //*=====BEGINNING OF CONSTRUCTOR FOR DATABASE PROPERTY ASSIGNMENT========*//
    //===================USE WITH PHP VERSION 8.0 OR HIGHER===================//
    /**
     * This constructor assigns the database Object using Constructor promotion
     * The values is added when you add a visibility to the function aurgument 
     *
     * @param object $_Database Contains the database connection
     * @param string $key       Contains the new refresh key
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function __construct(Database $_Database, string $key) 
    {
        $this->_conn = $_Database->connect();
        $this->_key  = $key;
    }
    //*=====================================================================*//

    //*=========================================================================*//
    /**
     * The createRefreshToken() FUNCTION CREATES A NEW RESOURCE 
     * 
     * @param string $token      This contains the refresh token payload
     * @param int    $expires_at This contains the refresh token experation time
     * 
     * @access public  
     * 
     * @return bool
     */
    public function createRefreshToken(string $token, int $expires_at): bool
    {
        
        $this->_expires_at = $expires_at;

        $this->_hash_token= hash_hmac("sha256", $token, $this->_key);

        $sql = "INSERT INTO $this->_table_name 
              (token_hash, expires_at) 
              VALUES (:token_hash, :expires_at)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":token_hash", $this->_hash_token, PDO::PARAM_STR);
        $stmt->bindValue(":expires_at", $this->_expires_at, PDO::PARAM_INT);
        $results = $stmt->execute();
        return $results;

    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * DeleteRefreshToken() DELETES THE REFRESH TOKEN FROM THE refresh_token TABLE 
     * 
     * @param string $token This contains the refresh token payload
     * 
     * @access public  
     * 
     * @return int
     */
    public function deleteRefreshToken(string $token): int
    {

        $this->_hash_token = hash_hmac("sha256", $token, $this->_key);

        $sql = "DELETE FROM $this->_table_name WHERE token_hash = :_hash_token";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":_hash_token", $this->_hash_token, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->rowCount();
        return $results;
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * The getRefreshToken() GETS THE REFRESH TOKEN FROM THE refresh_token TABLE 
     * 
     * @param string $token This contains the refresh token payload
     * 
     * @access public  
     * 
     * @return mixed
     */
    public function getRefreshToken(string $token)
    {

        $this->_hash_token = hash_hmac("sha256", $token, $this->_key);

        $sql = "SELECT * FROM $this->_table_name WHERE token_hash = :_hash_token";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bindValue(":_hash_token", $this->_hash_token, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
    //*=========================================================================*//

    //*=========================================================================*//
    /**
     * DeleteExpiredToken() DELETES THE EXPIRED TOKEN FROM THE refresh_token TABLE 
     * 
     * @access public  
     * 
     * @return int
     */
    public function deleteExpiredToken(): int
    {

        $sql = "DELETE FROM $this->_table_name WHERE expires_at < UNIX_TIMESTAMP()";
        $stmt = $this->_conn->query($sql);
        $results = $stmt->rowCount();
        return $results;
    }
    //*=========================================================================*//

}