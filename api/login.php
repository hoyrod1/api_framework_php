<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Login Page for Api To-Do-List
 * 
 * @category Php_API
 * @package  Src-Folder
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/login.com
 */

declare(strict_types=1);

ini_set("display_errors", "On");

require __DIR__ . "/bootstrap.php";

// RESTRICT THE METHOD USED TO ACCESS THIS END POINT TO "POST"
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  
    http_response_code(405);
    header("Allow: POST");
    exit;

}

$data = (array) json_decode(file_get_contents("php://input"), true);

if (! array_key_exists("username", $data) || ! array_key_exists("password", $data)) {
  
    http_response_code(400);
    echo json_encode(["message" => "Missing login credentials"]);
    exit;

}

echo json_encode($data);