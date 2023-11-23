<?php
/**
 * * @file
 * php version 7.4.33
 * 
 * Landing Page for Api To-Do-List
 * 
 * @category Php_Sdk
 * @package  Curl_Configuration
 * @author   Rodney St.Cloud <hoyrod1@aol.com>
 * @license  STC Media inc
 * @link     https://www.api-todolist.com
 */
declare(strict_types=1);

ini_set("display_errors", "On");

require "vendor/autoload.php";

set_exception_handler("ErrorHandler::handleException");

// use Src\TaskController\TaskController;

$url_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$url_parts = explode("/", $url_path);

$resource = $url_parts[2];
$id = $url_parts[3] ?? null;

if ($resource != "tasks") {
    // header("HTTP/1.1 404 Not Found");
    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    http_response_code(404);
    exit;
}

header("Content-Type: application/json; charset=UTF-8");

$controller = new TaskController;

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);