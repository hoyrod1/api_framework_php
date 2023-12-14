<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Refresh Access Token Page for Api To-Do-List
 * 
 * @category Refresh_Access_Token
 * @package  Src-Folder
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/api/refresh.com
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

// RETRIEVE THE ENCODED PAYLOAD IN THE "token" KEY FROM THE REQUEST BODY"
// CONVERT THE JSON STRING INTO AN ASSOCIATE ARRAY
$data = (array) json_decode(file_get_contents("php://input"), true);

// CHECK IF THE "token" KEY EXIST IN THE ASSOCIATE ARRAY
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

//==================CREATE A NEW RefreshTokenGateway Object====================//
//========PASS THE DATABASE OBJECT AND THE SECRET KEY IN AS THE ARGUMENT=======//
$RefreshTokenGateway = new RefreshTokenGateway($database, $_ENV['SECRET_KEY']);
//=============================================================================//

//=============VALIDATE IF THE REFRESH TOKEN EXIST IN THE DATABASE=============//
//=======PASS IN THE VALUE OF THE TOKEN FROM THE REQUEST AS THE ARGUMENT=======//
$Valid_Refresh_Token = $RefreshTokenGateway->getRefreshToken($data["token"]);

if ($Valid_Refresh_Token === false) {
  
    http_response_code(400);
    echo json_encode(["message" => "Invalid token (not on whitelist)"]);
    exit;

}
//=============================================================================//

$users_gateway = new UsersGateway($database);

$user = $users_gateway->getUserByRefreshTokenId($refresh_user_id);

if ($user === false) {
  
    http_response_code(401);
    echo json_encode(["message" => "Invalid Authentication"]);
    exit;

}


//=============================================================================//
//   LOGIC CAN BE ADD TO CHECK IF THE USERS ACCOUNT IS DISABLED   //
// WITH A "boolean" COLUMN IN THE RECORDS THAT CAN BE SET TO FALSE //

//=============================================================================//


//=============================================================================//
//REQUIRE THE token.php FILE TO GENERATE A NEW Access Token AND A Refresh token//
require __DIR__ . "/tokens.php";
//=============================================================================//


//==============DELETE THE EXISTING REFRESH TOKEN IN THE DATABASE==============//
//=======PASS IN THE VALUE OF THE TOKEN FROM THE REQUEST AS THE ARGUMENT=======//
$delRefreshToken = $RefreshTokenGateway->deleteRefreshToken($data["token"]);
//=============================================================================//


//=============================================================================//
//===CALL THE createRefreshToken() METHOD FROM THE RefreshTokenGateway Object==//
//========PASS THE DATABASE OBJECT AND THE SECRET KEY IN AS THE ARGUMENT=======//
$createRefreshToken = $RefreshTokenGateway->createRefreshToken(
    $encoded_refresh_token, 
    $refresh_token_expiry
);
//=============================================================================//