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

require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("ErrorHandler::handleErrors");
set_exception_handler("ErrorHandler::handleException");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

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

$api_key = $_SERVER["HTTP_X_API_KEY"];

echo $api_key;
exit;

header("Content-Type: application/json; charset=UTF-8");

$database = new Database(
    $_ENV['DB_HOST'], 
    $_ENV['DB_USER'], 
    $_ENV['DB_PASS'], 
    $_ENV['DB_NAME']
);

$taskGateway = new TasksGateway($database);

$controller = new TaskController($taskGateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);