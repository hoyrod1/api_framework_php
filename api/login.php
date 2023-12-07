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

// RETRIEVE THE username AND password FROM THE REQUEST BODY"
// CONVERT THE JSON STRING INTO AN ASSOCIATE ARRAY
$data = (array) json_decode(file_get_contents("php://input"), true);

// CHECK IF THE LOGIN CREDENTIALS EXIST IN THE ASSOCIATE ARRAY
if (! array_key_exists("username", $data) || ! array_key_exists("password", $data)) {
  
    http_response_code(400);
    echo json_encode(["message" => "Missing login credentials"]);
    exit;

}

// NEED TO ADD LOGIN VALIDATION AND ERROR CHECK //
// $username = $data["username"];
// password  = $data["password"];

$database = new Database(
    $_ENV['DB_HOST'], 
    $_ENV['DB_USER'], 
    $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']
);

$users_gateway = new UsersGateway($database);

$username = $data["username"];

$user = $users_gateway->getUserByUserName($username);

if ($user === false) {
  
    http_response_code(401);
    echo json_encode(["message" => "Invalid Login Credentials"]);
    exit;

}

$password_verified = password_verify($data["password"], $user["password_hash"]);

if ($password_verified === false) {
  
    http_response_code(401);
    echo json_encode(["message" => "Invalid Login Credentials"]);
    exit;

}

//=================CREATE AN ACCESS TOKEN TO RETRIEVE USER DATA================//
//THE ACCESS TOKEN WILL BE A SINGLE STRING WHICH IS SENT IN THE REQUEST HEADER//
//==========THIS ACCESS TOKEN WILL BE GENERATED USING base64_encode()==========//

// FIRST STORE THE AUTHENTICATED USER DATA IN AN ASSOCIATIVE ARRAY
$payload = [
    "sub" => $user["id"],
    "name" => $user["name"]
];
//============================== CODE FOR TESTING ==============================//
// SECOND CONVERT THE ASSOCIATIVE ARRAY TO JSON STRING USING json_encode() 
//$json_payload = json_encode($payload);
// THIRD USE base64_encode TO CONVERT THE JSON STRING TO SIMPLE STRING OF CHARACTERS
//$access_token = base64_encode($json_payload);
//echo json_encode(["access token" => $access_token]);
//===============================================================================//

$codec = new JWTCodec();
$access_token = $codec->encode($payload);
echo json_encode(["access token" => $access_token]);
