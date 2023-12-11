<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Login Page for Api To-Do-List
 * 
 * @category Php_API
 * @package  Src-Folder
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/refresh.com
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
if (! array_key_exists("token", $data)) {
  
    http_response_code(400);
    echo json_encode(["message" => "Missing token"]);
    exit;

}

//=====CREATE A NEW JWTCodec Object PASS THE SECRET KEY IN AS THE ARGUMENT========//
//===CALL THE decode() FUNCTION AND PASS THE $data["token"] IN AS THE ARGUMENT====//
$JWTcodec = new JWTCodec($_ENV['SECRET_KEY']);

try {

    $refresh_payload = $JWTcodec->decode($data["token"]);

} catch (Exception) {

    http_response_code(400);
    echo json_encode(["message" => "Invalid token"]);
    exit;

}

$refresh_user_id = (int) $refresh_payload["sub"];

$database = new Database(
    $_ENV['DB_HOST'], 
    $_ENV['DB_USER'], 
    $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']
);

$users_gateway = new UsersGateway($database);

$user = $users_gateway->getUserByRefreshTokenId($refresh_user_id);

if ($user === false) {
  
    http_response_code(401);
    echo json_encode(["message" => "Invalid Authentication"]);
    exit;

}
var_dump($user);
exit;
//=================================================================================//