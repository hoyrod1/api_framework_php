<?php
/**
 * * @file
 * php version 8.2.0
 * 
 * Page delete the expired Refresh Token Database Table
 * 
 * @category Delete_Refresh_Token_Configuration
 * @package  Delete_Refresh_Token_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks/src/delete-expired-refresh-tokens.php
 */

declare(strict_types=1);

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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


//==============DELETE THE EXPIRED REFRESH TOKEN IN THE DATABASE===============//
//======ECHO OUT THE RESUTS RETURNED FROM THE deleteExpiredToken() METHOD======//
$delExpiredRefreshToken = $RefreshTokenGateway->deleteExpiredToken();
echo $delExpiredRefreshToken . "\n";
//=============================================================================//

