<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Landing Page for Api To-Do-List
 * 
 * @category Php_API
 * @package  Vendor-Composer-.env-src
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.tasks.com
 */

declare(strict_types=1);

ini_set("display_errors", "On");

require __DIR__ . "/bootstrap.php";

// use Src\TaskController\TaskController;

$url_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$url_parts = explode("/", $url_path);

$resource = $url_parts[3];
$id = $url_parts[4] ?? null;

if ($resource != "tasks") {
    // header("HTTP/1.1 404 Not Found");
    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    http_response_code(404);
    exit;
}

$database = new Database(
    $_ENV['DB_HOST'], 
    $_ENV['DB_USER'], 
    $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']
);

$users_gateway = new UsersGateway($database);

$JWTCodec = new JWTCodec($_ENV['SECRET_KEY']);

//===USE THE $_SERVER["HTTP_AUTHORIZATION"] IF THE .htaccess HAS BEEN CONFGURED===//
// $access_token = $_SERVER["HTTP_AUTHORIZATION"];
// var_dump($access_token);
// exit;

//===USE THE apache_request_headers() IF THE .htaccess HAS NOT BEEN CONFGURED===//
// $headers = apache_request_headers();
// print_r($headers);
// exit;

$auth = new Auth($users_gateway, $JWTCodec);

// USE FOR AUTHENTICATING APIKeys
// $validated_auth = $auth->authenticateAPIKey();

// USE FOR AUTHENTICATING Access Tokens
$validated_auth = $auth->authenticateAccessToken();

if (! $validated_auth) {
    exit;
}

// THE auth->getUsersID() RETURNS THE USERS "id" 
// AFTER AUTHENTICATING WITH A "APIKeys" OR "AccessTokens"
$user_id = $auth->getUsersID();

$taskGateway = new TasksGateway($database);

$controller = new TaskController($taskGateway, $user_id);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);